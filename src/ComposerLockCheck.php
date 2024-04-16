<?php

namespace Ajt\ComposerLockCheck;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\PluginException;

class ComposerLockCheck implements PluginInterface
{
    private $patterns;

    public function activate(Composer $composer, IOInterface $io)
    {
        // Retrieve custom patterns from composer.json extra section
        $extra = $composer->getPackage()->getExtra();
        $this->patterns = isset($extra['composer-package-patterns']) ? $extra['wordpress-plugin-patterns'] : [];
        $this->patterns[] = [
            "pattern" => "/https:\/\/downloads\.wordpress\.org\/plugin\/[a-zA-Z0-9-]+\.zip\?timestamp=\d+/",
            "description" => "WordPress plugin default pattern",
            "error_message" => "WordPress plugin found in the composer.lock file using timestamp versioning!"
        ];

        // Check composer.lock for the patterns
        $lockFile = $composer->getConfig()->getConfigSource()->getName();
        $lockContents = json_decode(file_get_contents($lockFile), true);

        foreach ($this->patterns as $pattern) {
            foreach ($lockContents['packages'] as $package) {
                if (isset($package['dist']) && preg_match($pattern['pattern'], $package['dist']['url'])) {
                    $io->write($pattern['error_message']);
                    throw new PluginException($pattern['error_message'] . ' URL: ' . $package['dist']['url']);
                }
            }
        }
    }

    public function deactivate(Composer $composer, IOInterface $io)
    {
        // Cleanup if necessary
    }

    public function uninstall(Composer $composer, IOInterface $io)
    {
        // Cleanup if necessary
    }
}
