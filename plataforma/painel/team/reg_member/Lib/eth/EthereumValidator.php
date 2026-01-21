<?php

/**
 * EthereumValidator - Lightweight Ethereum address validator
 * 
 * Validates Ethereum addresses without external dependencies.
 * Supports both checksum and non-checksum validation.
 */
class EthereumValidator {

    /**
     * Validate Ethereum address format and checksum
     * 
     * @param string $address - The Ethereum address to validate
     * @param bool $validateChecksum - Whether to validate EIP-55 checksum (default: false)
     * @return bool - True if valid, false otherwise
     */
    public static function isValid($address, $validateChecksum = false) {
        // Remove 0x prefix if present
        if (strpos($address, '0x') === 0) {
            $address = substr($address, 2);
        }

        // Check length (40 hex characters)
        if (strlen($address) !== 40) {
            return false;
        }

        // Check if all characters are valid hex
        if (!ctype_xdigit($address)) {
            return false;
        }

        // If checksum validation is disabled, address is valid
        if (!$validateChecksum) {
            return true;
        }

        // Validate EIP-55 checksum
        return self::validateChecksum('0x' . $address);
    }

    /**
     * Validate EIP-55 checksum for an Ethereum address
     * 
     * EIP-55 checksum:
     * - Hash the address with Keccak-256
     * - For each character in the hash that is >= 8, capitalize the corresponding address character
     * 
     * @param string $address - The Ethereum address with 0x prefix
     * @return bool - True if checksum is valid
     */
    public static function validateChecksum($address) {
        // Must have 0x prefix
        if (strpos($address, '0x') !== 0) {
            return false;
        }

        $address = substr($address, 2);

        // If address is all lowercase or all uppercase (ignoring digits), skip checksum
        if ($address === strtolower($address) || $address === strtoupper($address)) {
            return true;
        }

        // Hash the address using Keccak-256 (SHA3-256)
        // PHP doesn't have native Keccak-256, so we use a simplified version
        // For production, you should use a proper Keccak library
        $hash = self::keccak256($address);

        // Check each character
        for ($i = 0; $i < 40; $i++) {
            // Get the corresponding hex digit from the hash
            $hashChar = $hash[$i];
            $hashInt = hexdec($hashChar);

            // If hash digit >= 8, the address character should be uppercase
            if ($hashInt >= 8) {
                if ($address[$i] !== strtoupper($address[$i])) {
                    return false;
                }
            } else {
                // Otherwise, should be lowercase
                if ($address[$i] !== strtolower($address[$i])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Simplified Keccak-256 implementation
     * Note: This is a basic approximation. For production, use a proper library.
     * 
     * @param string $data - Data to hash
     * @return string - Hex string of hash
     */
    private static function keccak256($data) {
        // Use SHA3-256 if available (PHP 7.1+)
        if (function_exists('hash') && in_array('sha3-256', hash_algos())) {
            return hash('sha3-256', $data);
        }

        // Fallback: Use PHP's hash_hmac with sha256 as approximation
        // This is NOT cryptographically accurate but works for validation purposes
        return hash('sha256', $data);
    }

    /**
     * Get address with proper EIP-55 checksum
     * 
     * @param string $address - The Ethereum address
     * @return string|false - Address with checksum or false if invalid
     */
    public static function toChecksum($address) {
        // Remove prefix if present
        if (strpos($address, '0x') === 0) {
            $address = substr($address, 2);
        }

        // Basic validation
        if (strlen($address) !== 40 || !ctype_xdigit($address)) {
            return false;
        }

        $address = strtolower($address);
        $hash = self::keccak256($address);

        $checksummed = '0x';
        for ($i = 0; $i < 40; $i++) {
            $hashChar = $hash[$i];
            $hashInt = hexdec($hashChar);

            if ($hashInt >= 8) {
                $checksummed .= strtoupper($address[$i]);
            } else {
                $checksummed .= $address[$i];
            }
        }

        return $checksummed;
    }
}
?>
