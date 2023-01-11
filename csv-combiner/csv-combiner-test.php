#!/usr/bin/env phpunit
<?php

use PHPUnit\Framework\TestCase;

class CSVCombinerTest extends TestCase
{
    public function testBasic()
    {
        $testFile1 = 'test1.csv';
        $testFile2 = 'test2.csv';
        $testFile3 = 'test3.csv';
        $expectedFile = 'expected.csv';

        file_put_contents($testFile1, "header1,header2\ncontent1_1,content1_2\ncontent2_1,content2_2");
        file_put_contents($testFile2, "header1,header2\ncontent3_1,content3_2\ncontent4_1,content4_2");
        file_put_contents($testFile3, "header1,header2\ncontent5_1,content5_2\ncontent6_1,content6_2");
        file_put_contents($expectedFile, "header1,header2,filename\ncontent1_1,content1_2,test1.csv\ncontent2_1,content2_2,test1.csv\ncontent3_1,content3_2,test2.csv\ncontent4_1,content4_2,test2.csv\ncontent5_1,content5_2,test3.csv\ncontent6_1,content6_2,test3.csv\n");

        $output = shell_exec("./csv-combiner.php $testFile1 $testFile2 $testFile3");
        $this->assertEquals(file_get_contents($expectedFile), $output);

        unlink($testFile1);
        unlink($testFile2);
        unlink($testFile3);
        unlink($expectedFile);
    }

    public function testBadInput()
    {
        $output = shell_exec('./csv-combiner.php');
        $this->assertStringContainsString("Please specify the CSV files to process.", $output);
    }
}
