<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class ValidateMail extends Command
{
  protected function configure()
     {
         $this->setName('validate')
         // popraw descirption
             ->setDescription('This command check emails')
             ->setHelp('This command loads the file and searches it for invalid email addresses')
             ->addArgument('msg', InputArgument::REQUIRED, 'Pass a file name');
     }

     protected function execute(InputInterface $input, OutputInterface $output)
     {
// Load the file & prepear variables for handling file.
        $fileName = $input->getArgument('msg');
        $path =  __DIR__ . '/resources' .'/'. $fileName . '.csv';
        $insertedData = file_get_contents($path);
        $arr = explode("\n", $insertedData);
        $validEmailsSheet = fopen("validEmails.csv" ,"w");
        $validEmailCounter = 0;

// Prepear variables for handling invalid emails & raport
        $invalidEmailsSheet = fopen("invalidEmails.csv" ,"w");
        $invalidEmailCounter = 0;
        $validEmailCounter = 0;
        $raport = fopen("raport.txt");

         $pattern = "/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/";

         foreach ($arr as $key => $value) {
           if (!preg_match($pattern, $value)){
             $value .= "\n";
             fwrite($invalidEmailsSheet, $value);
             $invalidEmailCounter ++;
           } else {
             $value .= "\n";
             fwrite($validEmailsSheet, $value);
             $validEmailCounter ++;
           }

         }

         // var_dump($arr);
         // fclose($invalidEmailsSheet);
         return 0;
     }
}
