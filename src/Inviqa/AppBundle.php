<?php

namespace Inviqa;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Inviqa\DependencyInjection\CommandPass;

class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new CommandPass('app.console', 'app.console.command_loader', 'app.console.command'));
    }
}
