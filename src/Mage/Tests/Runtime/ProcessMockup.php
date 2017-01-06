<?php
/*
 * This file is part of the Magallanes package.
 *
 * (c) Andrés Montañez <andres@andresmontanez.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mage\Tests\Runtime;

use Symfony\Component\Process\Process;

class ProcessMockup extends Process
{
    protected $commandline;
    protected $timeout;
    protected $success = true;

    public function __construct($commandline, $cwd = null, array $env = null, $input = null, $timeout = 60, array $options = array())
    {
        $this->commandline = $commandline;
    }

    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    public function run($callback = null)
    {
        if ($this->commandline == 'ssh -p 22 -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no tester@host1 sh -c \"readlink -f /var/www/test/current\"') {
            $this->success = false;
        }

        if ($this->commandline == 'ssh -p 22 -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no tester@host3 sh -c \"ls -1 /var/www/test/releases\"') {
            $this->success = false;
        }
    }

    public function isSuccessful()
    {
        return $this->success;
    }

    public function getErrorOutput()
    {
        return '';
    }

    public function getOutput()
    {
        if ($this->commandline == 'git branch | grep "*"') {
            return '* master';
        }

        if ($this->commandline == 'ssh -p 22 -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no tester@testhost sh -c \"ls -1 /var/www/test/releases\"') {
            return implode(PHP_EOL, ['20170101015110', '20170101015111', '20170101015112', '20170101015113', '20170101015114', '20170101015115', '20170101015116', '20170101015117']);
        }

        if ($this->commandline == 'ssh -p 22 -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no tester@testhost sh -c \"readlink -f /var/www/test/current\"') {
            return '/var/www/test/releases/20170101015117';
        }

        if ($this->commandline == 'ssh -p 22 -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no tester@host1 sh -c \"ls -1 /var/www/test/releases\"') {
            return implode(PHP_EOL, ['20170101015110', '20170101015111', '20170101015112', '20170101015113', '20170101015114', '20170101015115', '20170101015116', '20170101015117']);
        }

        if ($this->commandline == 'ssh -p 22 -q -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no tester@host2 sh -c \"ls -1 /var/www/test/releases\"') {
            return '';
        }

        return '';
    }
}
