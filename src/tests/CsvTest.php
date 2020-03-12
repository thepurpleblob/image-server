<?php
use PHPUnit\Framework\TestCase;

require(dirname(__FILE__) . '/../../config.php');

class CsvTest extends TestCase {

    private $json = '';
    private $csv;

    protected function setUp(): void {
        global $CFG;

        $this->json = file_get_contents($CFG->testdatadir . '/csvupload.json');
        $this->csv = new \collection\lib\csv();
    }

    public function testProcess() {
        
        // Try with invalid json
        $response = $this->csv->process('garbage');
        $this->assertSame($response, 'Syntax error');

        $response = $this->csv->process($this->json);
        $this->assertTrue($response);
    }

    public function testHeaders() {
        $this->csv->process($this->json);
        $headers = $this->csv->verifyHeaders();
        $this->assertSame($headers, [
            "object_number",
            "title",
            "object_category",
            "description",
            "reproduction_reference"
        ]);
    }

    public function testLine() {
        $this->csv->process($this->json);

        $line = $this->csv->getLine(70);
        $this->assertSame($line, [
            '2017.205',
            'Notes',
            'ARCHIVE',
            "Driver's Notes for Class 158 Faults and Failures",
            "BONSR2017.2050 (Copy).jpg"
        ]);

        $this->csv->verifyHeaders();
        $result = $this->csv->processLine($line, 70);
        $this->assertEqualsCanonicalizing($result, [
            'institution_code' => '',
            'object_number' => "2017.2050",
            'title' => "Notes",
            'object_category' => "ARCHIVE",
            'description' => "Driver's Notes for Class 158 Faults and Failures",
            'reproduction_reference' => "BONSR2017.2050 (Copy).jpg",
            'error' => false
        ]);
    }

    public function testLines() {
        $this->csv->process($this->json);
        $this->csv->verifyHeaders();
        $processedlines = $this->csv->processLines();
   
        $this->assertSame(count($processedlines), 1146);

        $errors = $this->csv->getErrors();
        $this->assertSame($errors[0], [
            'offset' => 950,
            'dbfield' => "object_category",
            'error' => "Required"
        ]);
    }

}
