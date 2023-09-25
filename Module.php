<?php
/**
 * This code is licensed under AGPLv3 license or Afterlogic Software License
 * if commercial version of the product was purchased.
 * For full statements of the licenses see LICENSE-AFTERLOGIC and LICENSE-AGPL3 files.
 */

namespace Aurora\Modules\CreateMailServerPlugin;

/**
 * @license https://www.gnu.org/licenses/agpl-3.0.html AGPL-3.0
 * @license https://afterlogic.com/products/common-licensing Afterlogic Software License
 * @copyright Copyright (c) 2023, Afterlogic Corp.
 *
 * @property Settings $oModuleSettings
 *
 * @package Modules
 */
class Module extends \Aurora\System\Module\AbstractModule
{
    /**
     * Initializes Mail Module.
     *
     * @ignore
     */
    public function init()
    {
        $this->subscribeEvent('StandardLoginFormWebclient::Login::before', array($this, 'onBeforeLogin'));
    }

    /**
     * @return Module
     */
    public static function getInstance()
    {
        return parent::getInstance();
    }

    /**
     * @return Module
     */
    public static function Decorator()
    {
        return parent::Decorator();
    }

    /**
     * @return Settings
     */
    public function getModuleSettings()
    {
        return $this->oModuleSettings;
    }

    public function onBeforeLogin($aArgs, &$mResult)
    {
        $sDomain = \MailSo\Base\Utils::GetDomainFromEmail($aArgs['Login']);
        $oServer = false;
        $aGetMailServerResult = \Aurora\Modules\Mail\Module::Decorator()->GetMailServerByDomain($sDomain, /*AllowWildcardDomain*/false);
        if (!empty($aGetMailServerResult) && isset($aGetMailServerResult['Server']) && $aGetMailServerResult['Server'] instanceof \Aurora\Modules\Mail\Models\Server) {
            $oServer = $aGetMailServerResult['Server'];
        }

        if (!$oServer) {
            $bConnectValid = false;
            try {
                $bConnectValid = false;
                $oImapClient = \MailSo\Imap\ImapClient::NewInstance();
                $oImapClient->Connect($this->oModuleSettings->IncomingServer . $sDomain, $this->oModuleSettings->IncomingPort);
                $bConnectValid  = $oImapClient->Login($aArgs['Login'], $aArgs['Password']);
                $oImapClient->LogoutAndDisconnect();
            } catch (\Exception $oException) {
            }
            if ($bConnectValid) {
                \Aurora\System\Api::skipCheckUserRole(true);
                $iIdServer = \Aurora\Modules\Mail\Module::getInstance()->CreateServer(
                    $sDomain,
                    $this->oModuleSettings->IncomingServer . $sDomain,
                    $this->oModuleSettings->IncomingPort,
                    $this->oModuleSettings->IncomingUseSsl,
                    $this->oModuleSettings->OutgoingServer . $sDomain,
                    $this->oModuleSettings->OutgoingPort,
                    $this->oModuleSettings->OutgoingUseSsl,
                    $this->oModuleSettings->SmtpAuthType,
                    $sDomain,
                    $this->oModuleSettings->EnableThreading,
                    $this->oModuleSettings->EnableSieve,
                    $this->oModuleSettings->SievePort,
                    $this->oModuleSettings->SmtpLogin,
                    $this->oModuleSettings->SmtpPassword,
                    $this->oModuleSettings->UseFullEmailAddressAsLogin
                );
                \Aurora\System\Api::skipCheckUserRole(false);
            }
        }
    }
}
