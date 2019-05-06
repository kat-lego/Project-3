<?php

//html elements
define('NONE', '');
define('TEXT', '');
define('TABLE', 'table');
define('TABLEROW', 'tr');
define('TABLEDATA', 'td');
define('TABLEHEADER', 'th');

//attribute names


class HtmlElement{
	
	private $type;
	private $text;
	private $sub_elements;
	private $attributes;
	public $iscompound;

	/**
	* Constructer for an HtmlElement
	*/
	function __construct( $type,$text,$compound) {
		$this->sub_elements = array();
		$this->attributes = array();
		$this->iscompound = $compound;
		$this->text = $text;
		$this->type = $type;
	}


	public function add_attribute($name,$value){
		if(!is_string($name) ){
			throw new InvalidArgumentException("Input mismatch");
		}
		if( !is_string($value) && !is_integer($value)){
			throw new InvalidArgumentException("Input mismatch");
		}

		$this->attributes[$name] = $value;
		return $this;
	}

	public function add_attributes($params){
		foreach ($params as $key => $value) {
			if(!is_string($key)){
				throw new InvalidArgumentException("input missmatch");
			}
			if(!is_string($value) && !is_integer($value) ){
				throw new InvalidArgumentException("input missmatch");
			}
		}

		foreach ($params as $key => $value) {
			$this->attributes[$key] = $value;
		}

	}

	/**
	* @codeCoverageIgnore
	*/
	public function get_attributes(){
		return $this->attributes;
	}

	/**
	* @codeCoverageIgnore
	*/
	public function get_attribute($name){
		 return $this->attribute[$name];
	}

	public function add_subelement($element){
		if(!$this->iscompound){
			throw new LogicException("This element does not allow for addition of sub elements");
		}
		if(! $element instanceof HtmlElement){
			throw new InvalidArgumentException("element is supposed to be of thy HtmlElement");
		}
		$this->sub_elements[] = $element;
		return $this;
	}

	public function add_subelements($array){
		if(!$this->iscompound){
			throw new LogicException("This element does not allow for addition of sub elements");
		}

		foreach ($array as $key => $value) {
			if(! $value instanceof HtmlElement){
				throw new InvalidArgumentException("One of the elements are not of type HtmlElement");
			}
		}

		foreach ($array as $key => $value) {
			$this->sub_elements[] = $value;
		}
	}

	public function get_subelements(){
		return $this->sub_elements;
	}

	public function str(){
		if(!$this->iscompound){
			return $this->text;
		}

		$string = '<'.$this->type;

		//style attribute
		$string.= ' style="';
		foreach ($this->attributes as $key => $value) {
			$string.="$key:$value;";
		}
		$string.='"';

		$string.=" >";

		foreach ($this->sub_elements as $key => $value) {
			$string.=$value->str();
		}
		$string.= '</'.$this->type.'>';
		return $string;
	}


	public static function create_html_table($header,$table_attributes,$header_attributes){
		$table = new HtmlElement(TABLE,null,True);
		$table->add_attributes($table_attributes);
		
		$header_row = new HtmlElement(TABLEROW,null,True);
		$header_row->add_attributes($header_attributes);
		foreach ($header as $key => $value) {
			$h = new HtmlElement(TABLEHEADER,null,True);
			$h->add_subelement(new HtmlElement(TEXT,$value,false));
			$header_row->add_subelement($h);	
		}
		$table->add_subelement($header_row);
		return $table;
	}

	public static function add_tabledata($table,$rowRata,$row_attributes){
		$data_row = new HtmlElement(TABLEROW,null,True);
		$data_row->add_attributes($row_attributes);

		foreach ($rowRata as $key => $value) {
			$h = new HtmlElement(TABLEDATA,null,True);
			$h->add_subelement(new HtmlElement(TEXT,$value,false));
			$data_row->add_subelement($h);	
		}
		$table->add_subelement($data_row);

	}




}
