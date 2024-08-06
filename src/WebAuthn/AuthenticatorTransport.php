<?php

declare(strict_types=1);

namespace SnapAuth\WebAuthn;

/**
 * @link https://www.w3.org/TR/webauthn-3/#enum-transport
 */
enum AuthenticatorTransport: string
{
    /**
     * Bluetooth Low Energy
     */
    case Ble = 'ble';

    /**
     * Smart Cards
     */
    case SmartCard = 'smart-card';

    /**
     * Mixed transport methods, including (but not limited to) Cross-Device
     * Authentication
     */
    case Hybrid = 'hybrid';

    /**
     * Platform authenticators, such as system-managed credential managers
     */
    case Internal = 'internal';

    /**
     * Near-Field Communication
     */
    case Nfc = 'nfc';

    /**
     * Removable USB devices
     */
    case Usb = 'usb';
}
