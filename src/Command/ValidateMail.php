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
        $validEmailsSheet = fopen("validEmailsSheet.csv" ,"w");
        $validEmailCounter = 0;

// Prepear variables for handling invalid emails & raport
        $invalidEmailsSheet = fopen("invalidEmailsSheet.csv" ,"w");
        $invalidEmailCounter = 0;
        $validEmailCounter = 0;
        $raport = fopen("raport.txt" ,"w");


         foreach ($arr as $key => $email) {
           if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
             print $email . "\n";

             $pieces = explode("@", $email);
             $domain = $pieces[1];

             if ( checkdnsrr($domain, 'MX') ) {
               $email .= "\n";
               fwrite($validEmailsSheet, $email);
               $validEmailCounter ++;
              }
           }
           else {
             $email .= "\n";
             fwrite($invalidEmailsSheet, $email);
             $invalidEmailCounter ++;
           }
         }

         fwrite($raport,
          'Liczba poprawnych adresów email wynosi : ' . "\t" . $validEmailCounter . "\n" .
          'Liczba niepoprawnych adresów email wynosi : ' . "\t" . $invalidEmailCounter . "\n"
          );

         return 0;
     }
}
