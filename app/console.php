<?php


namespace App;


class console
{
    public static function log($message){
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        $output->writeln("<info>".$message."</info>");
    }
}
