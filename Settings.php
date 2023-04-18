<?php
/**
 * This code is licensed under AGPLv3 license or Afterlogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\CreateMailServerPlugin;

use Aurora\System\SettingsProperty;

/**
 * @property bool $Disabled"
 * @property string $IncomingServer"
 * @property int $IncomingPort"
 * @property bool $IncomingUseSsl"
 * @property string $OutgoingServer"
 * @property int $OutgoingPort"
 * @property bool $OutgoingUseSsl"
 * @property int $SmtpAuthType"
 * @property bool $EnableThreading"
 * @property bool $UseFullEmailAddressAsLogin"
 * @property bool $EnableSieve"
 * @property int $SievePort"
 * @property string $SmtpLogin"
 * @property string $SmtpPassword"
 */

class Settings extends \Aurora\System\Module\Settings
{
    protected function initDefaults()
    {
        $this->aContainer = [
            "Disabled" => new SettingsProperty(
                false,
                "bool",
                null,
                ""
            ),
            "IncomingServer" => new SettingsProperty(
                "",
                "string",
                null,
                "",
            ),
            "IncomingPort" => new SettingsProperty(
                143,
                "int",
                null,
                "",
            ),
            "IncomingUseSsl" => new SettingsProperty(
                false,
                "bool",
                null,
                "",
            ),
            "OutgoingServer" => new SettingsProperty(
                "",
                "string",
                null,
                "",
            ),
            "OutgoingPort" => new SettingsProperty(
                25,
                "int",
                null,
                "",
            ),
            "OutgoingUseSsl" => new SettingsProperty(
                false,
                "bool",
                null,
                "",
            ),
            "SmtpAuthType" => new SettingsProperty(
                2,
                "int",
                null,
                "",
            ),
            "EnableThreading" => new SettingsProperty(
                true,
                "bool",
                null,
                "",
            ),
            "UseFullEmailAddressAsLogin" => new SettingsProperty(
                true,
                "bool",
                null,
                "",
            ),
            "EnableSieve" => new SettingsProperty(
                false,
                "bool",
                null,
                "",
            ),
            "SievePort" => new SettingsProperty(
                4190,
                "int",
                null,
                "",
            ),
            "SmtpLogin" => new SettingsProperty(
                "",
                "string",
                null,
                "",
            ),
            "SmtpPassword" => new SettingsProperty(
                "",
                "string",
                null,
                "",
            ),
        ];
    }
}
