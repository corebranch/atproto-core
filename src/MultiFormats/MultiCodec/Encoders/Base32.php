<?php declare(strict_types = 1);

/**
 * This file is part of the ATProto Core package.
 *
 * (c) Core Branch
 *
 * This source code is licensed under the MIT license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace ATProto\Core\MultiFormats\MultiCodec\Encoders;

use ATProto\Core\MultiFormats\Interfaces\EncoderInterface;
use ValueError;

/**
 * Class Base32
 *
 * Encodes data into Base32 format as specified by RFC 4648.
 *
 * @see https://datatracker.ietf.org/doc/html/rfc4648#autoid-11
 * Reference to RFC 4648 for the Base32 encoding specification.
 */
class Base32 implements EncoderInterface
{
    /**
     * @var array<string|int> BASE32_ALPHABET
     *
     * The Base32 alphabet used to encode 5-bit binary chunks into printable characters.
     */
    private const array BASE32_ALPHABET = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        2, 3, 4, 5, 6, 7
    ];

    /**
     * Encodes the given string data into Base32 format.
     *
     * @param  string  $data Input data to be encoded.
     * @return string  Base32-encoded representation of the input data.
     */
    public static function encode(string $data): string
    {
        // Convert the string into a binary representation
        $binaryString = \array_reduce(\str_split($data), static function (?string $binaryString, string $char): string {
            return $binaryString . \str_pad(
                    \decbin(\ord($char)),
                    8,
                    '0',
                    STR_PAD_LEFT
                );
        });

        // Split the binary string into 5-bit chunks and encode each chunk
        $encoded = \array_reduce(\str_split($binaryString, 5), static function (?string $encoded, string $block): string {
            if (\strlen($block) < 5) {
                $block = \str_pad($block, 5, '0');
            }

            return $encoded . self::BASE32_ALPHABET[\bindec($block)];
        });

        // Padding function to append '=' for incomplete groups
        $fill = static fn (int $len) => \str_repeat('=', $len);

        // Determine and apply padding as per RFC 4648
        return $encoded . match (\strlen($binaryString) % 40) {
                0  => '',
                8  => $fill(6),
                16 => $fill(4),
                24 => $fill(3),
                32 => $fill(1),
            };
    }

    /**
     * Decodes a Base32-encoded string back to its original binary form.
     *
     * @param  string  $data Base32-encoded input string.
     * @return string  Decoded original data.
     *
     * @throws ValueError If the input string contains invalid Base32 characters.
     */
    public static function decode(string $data): string
    {
        // Validate input: Allow only Base32 characters (A-Z, 2-7) with optional padding ('=')
        if (! \preg_match('/^[A-Z2-7]+=*$/', $data)) {
            throw new ValueError("Invalid Base32 encoded data.");
        }

        $map = \array_flip(self::BASE32_ALPHABET);

        // Remove padding characters ('=') from the input
        $data = \rtrim($data, '=');

        // Convert each character to its 5-bit binary representation
        $binaryString = \array_reduce(
            \str_split($data),
            static function (string $binaryString, string $char) use ($map): string {
                return $binaryString . \str_pad(
                        \decbin($map[$char]), // Map character to 5-bit binary
                        5,
                        '0',
                        STR_PAD_LEFT
                    );
            },
            initial: ''
        );

        // Combine binary groups into 8-bit chunks and decode to original characters
        return \array_reduce(\str_split($binaryString, 8), static function (string $decoded, string $block): string {
                if (\strlen($block) === 8) { // Process only complete 8-bit blocks
                    $decoded .= \chr(\bindec($block));
                }
                return $decoded;
            },
            initial: ''
        );
    }
}
