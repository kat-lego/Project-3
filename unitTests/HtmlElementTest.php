<?php
use PHPUnit\Framework\TestCase;



class HtmlAPITest extends TestCase
{
	
	public function test_create_htmlelement(){
		$element = new HtmlElementPrototype('test',null,True);
		$this->assertTrue($element!=null);
	}

	public function test_add_attribute(){
		$element = new HtmlElementPrototype('test',null,True);
		$element->add_attribute('attribute1','10');
		$this->assertEquals($element->get_attributes(),['attribute1'=>10]);

		//chain calls
		$element->add_attribute('attribute2','10')->add_attribute('attribute3','10');
		$this->assertEquals($element->get_attributes(),["attribute1"=>10, "attribute2"=>10, "attribute3"=>10]);

		//test for invalid arguments
		$this->expectException(InvalidArgumentException::class);
		$element->add_attribute(True,10);

	}


	public function test_add_attributes(){
		$element = new HtmlElementPrototype('test',null,True);
		$params = ["attribute1"=>10, "attribute2"=>10, "attribute3"=>10];
		$element->add_attributes($params);
		$this->assertEquals($element->get_attributes(),$params);

		//test for invalid arguments
		$this->expectException(InvalidArgumentException::class);
		$element->add_attributes([True=>"20","hey"=>null]);
	}


	public function test_add_sub_element_for_compounds(){
		$element = new HtmlElementPrototype('test',null,true);
		//element is of type htmlelement
		$sub_element = new HtmlElementPrototype('test',null,true);
		$element->add_subelement($sub_element);
		$arr = $element->get_subelements();
		$this->assertContains($sub_element,$arr);

		//element is not of type htmlelement
		$this->expectException(InvalidArgumentException::class);
		$sub_element2 = new stdClass();
		$element->add_subelement($sub_element2);
		
	}

	public function test_add_sub_element_for_noncompounds(){
		$this->expectException(LogicException::class);
		$element = new HtmlElementPrototype('test','test',false);
		$sub_element = new HtmlElementPrototype('test',null,true);
		$element->add_subelement($sub_element);
	}


	public function test_add_sub_elements_for_compounds(){
		$element = new HtmlElementPrototype('test',null,true);
		$sub_elements = [
			new HtmlElementPrototype('test',null,true),
			new HtmlElementPrototype('test',null,true)
		];

		$element->add_subelements($sub_elements);
		$arr = $element->get_subelements();
		foreach ($sub_elements as $key => $value) {
			$this->assertContains($value,$arr);
		}

		$this->expectException(InvalidArgumentException::class);
		$sub_elements[] = new stdClass();
		$element->add_subelement($sub_elements);


	}

	public function test_add_sub_elements_for_noncompounds(){
		$this->expectException(LogicException::class);
		$element = new HtmlElementPrototype('test','test',false);
		$sub_elements = [
			new HtmlElementPrototype('test',null,true),
			new HtmlElementPrototype('test',null,true)
		];
		$element->add_subelement($sub_elements);
	}
	public function test_str_for_noncompounds(){
		$element = new HtmlElementPrototype('test',"Yey",false);
		$this->assertEquals($element->str(),'Yey');
	}

	public function test_str_for_compounds(){
		//without attributes]
		$element = new HtmlElementPrototype('h1',null,True);
		$element->add_subelement(new HtmlElementPrototype('test',"Yey",false));
		$this->assertEquals($element->str(),'<h1 style="" >Yey</h1>');

		//with attributes
		$params = ["attribute1"=>10,"attribute2"=>10];
		$element->add_attributes($params);
		$this->assertEquals($element->str(),'<h1 style="attribute1:10;attribute2:10;" >Yey</h1>');

	}
	
	public function test_create_html_tableheader(){
		$header = ['Column1','Column2'];
		$table_attributes = ['width'=>'100%'];
		$header_attributes = [];
		$element = HtmlElementPrototype::create_html_table($header,$table_attributes,$header_attributes);
		$table =
		"<table style=\"width:100%;\" >".
			"<tr style=\"\" >".
				"<th style=\"\" >Column1</th>".
				"<th style=\"\" >Column2</th>".
			"</tr>".
		"</table>";
		$this->assertEquals($element->str(),$table);

	}

	public function test_add_tabledata(){
		$header = ['Column1','Column2'];
		$table_attributes = ['width'=>'100%'];
		$header_attributes = [];
		$element = HtmlElementPrototype::create_html_table($header,$table_attributes,$header_attributes);

		$data = ['data1','data2'];
		$row_attribute = [];

		HtmlElementPrototype::add_tabledata($element, $data, $row_attribute);
		$table =
		"<table style=\"width:100%;\" >".
			"<tr style=\"\" >".
				"<th style=\"\" >Column1</th>".
				"<th style=\"\" >Column2</th>".
			"</tr>".
			"<tr style=\"\" >".
				"<td style=\"\" >data1</td>".
				"<td style=\"\" >data2</td>".
			"</tr>".
		"</table>";

		$this->assertEquals($element->str(),$table);


	}

}
