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
 * @copyright Copyright (c) 2020, Afterlogic Corp.
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
                $oImapClient->Connect($this->getConfig('IncomingServer').$sDomain, $this->getConfig('IncomingPort'));
                $bConnectValid  = $oImapClient->Login($aArgs['Login'], $aArgs['Password']);
                $oImapClient->LogoutAndDisconnect();
            } catch (\Exception $oException) {
            }
            if ($bConnectValid) {
                \Aurora\System\Api::skipCheckUserRole(true);
                $iIdServer = \Aurora\Modules\Mail\Module::getInstance()->CreateServer(
                    $sDomain,
                    $this->getConfig('IncomingServer').$sDomain,
                    $this->getConfig('IncomingPort'),
                    $this->getConfig('IncomingUseSsl'),
                    $this->getConfig('OutgoingServer').$sDomain,
                    $this->getConfig('OutgoingPort'),
                    $this->getConfig('OutgoingUseSsl'),
                    $this->getConfig('SmtpAuthType'),
                    $sDomain,
                    $this->getConfig('EnableThreading'),
                    $this->getConfig('EnableSieve'),
                    $this->getConfig('SievePort'),
                    $this->getConfig('SmtpLogin'),
                    $this->getConfig('SmtpPassword'),
                    $this->getConfig('UseFullEmailAddressAsLogin')
                );
                \Aurora\System\Api::skipCheckUserRole(false);
            }
        }
    }
}
