<?php

abstract class HtmlElement{
	
	protected $openTag;
	protected $closeTag;
	protected $attributes;


	function __construct( $openTag, $closeTag ) {
		$this->attributes = array();
		$this->openTag = $openTag;
		$this->closeTag = $closeTag;
	}

	abstract function str();

	
	public function addAttribute($name,$value){
		$this->attributes[$name] = $value;
		return true;
	}

}

abstract class CompoundHtmlElement extends HtmlElement{
	protected $sub_elements;
	protected $attributes;
 
	function __construct( $openTag, $closeTag ) {
		$this->attributes =  array();
		$this->sub_elements =  array();

		parent::__construct($openTag,$closeTag);
	}

	public function addSubElement($id,$element){
		$this->sub_elements[$id] = $element;
		return true;
	}

	public function str(){
		
		$string = $this->openTag[0];
		
		foreach ($this->attributes as $key => $value) {
			$string.= " style = '$key:$value'";
		}
		
		$string.=$this->openTag[1];
		
		foreach($this->sub_elements as $key => $value){
			$string.=$value->str();
		}

		$string.=$this->closeTag;

		return $string;

	}

}


class HTMLTable extends CompoundHtmlElement{

	function __construct() {
		$openTag = array("<table",">");
		$closeTag = "</table>";

		parent::__construct($openTag,$closeTag);
	}

	function print(){
		foreach ($this->sub_elements as $key => $value) {
			echo $value->str();
		}
	}

}

class HTMLTableRow extends CompoundHtmlElement{

	function __construct() {
		$openTag = array("<tr",">");
		$closeTag = "</tr>";

		parent::__construct($openTag,$closeTag);
	}

}


class HTMLTableHeader extends CompoundHtmlElement{
	private $text;
	function __construct($text) {
		$this->text = $text;
		$openTag = array("<th",">");
		$closeTag = "</th>";
		parent::__construct($openTag,$closeTag);
	}

	public function str(){
		$string= $this->openTag[0];
		
		foreach ($this->attributes as $key => $value) {
			$string.= " style = '$key:$value'";
		}
		$string.=$this->openTag[1];
		$string.=$this->text;
		return $string.$this->closeTag;
	}

}

class HTMLTableData extends CompoundHtmlElement{
	private $text;
	function __construct($text) {
		$this->text = $text;
		$openTag = array("<td",">");
		$closeTag = "</td>";
		parent::__construct($openTag,$closeTag);
	}

	public function str(){
		$string= $this->openTag[0];
		
		foreach ($this->attributes as $key => $value) {
			$string.= " style = '$key:$value'";
		}
		$string.=$this->openTag[1];
		$string.=$this->text;
		return $string.$this->closeTag;
	}

}



?>