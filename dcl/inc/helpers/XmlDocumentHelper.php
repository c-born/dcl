<?php
/*
 * This file is part of Double Choco Latte.
 * Copyright (C) 1999-2011 Free Software Foundation
 *
 * Double Choco Latte is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * Double Choco Latte is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 * Select License Info from the Help menu to view the terms and conditions of this license.
 */

class XmlDocumentHelper
{
	var $root;
	var $parser;
	var $currentNode;
	var $nodes;

	public function __construct()
	{
		$this->root = NULL;
		$this->parser = NULL;
		$this->currentNode = NULL;
		$this->nodes = array();
	}

	public function ParseString($sXML)
	{
		$this->parser = xml_parser_create();
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, 'StartElement', 'EndElement');
		xml_set_character_data_handler($this->parser, 'DataElement');

		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);

		if (!xml_parse($this->parser, $sXML, true))
		{
			LogError(sprintf(STR_CMMN_PARSEERR, 'XML string',
					xml_error_string(xml_get_error_code($this->parser)),
					xml_get_current_line_number($this->parser)),
				__FILE__, __LINE__, debug_backtrace());
					
			throw new InvalidDataException();
		}

		xml_parser_free($this->parser);
	}

	public function ParseFile($sFileName)
	{
		$this->parser = xml_parser_create();
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, 'StartElement', 'EndElement');
		xml_set_character_data_handler($this->parser, 'DataElement');

		xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);

		if (!($fp = @fopen($sFileName, 'r')))
			throw new InvalidDataException(sprintf(STR_CMMN_FILEOPENERR, $sFileName));

		while ($sXML = fread($fp, 4096))
		{
			if (!xml_parse($this->parser, $sXML, feof($fp)))
			{
				throw new InvalidDataException(sprintf(STR_CMMN_PARSEERR, $sFileName,
						xml_error_string(xml_get_error_code($this->parser)),
						xml_get_current_line_number($this->parser)));
			}
		}

		fclose($fp);
		xml_parser_free($this->parser);
	}

	public function AddChildNode(&$oParent, $sName, $aAttributes)
	{
		$oNew = new XmlNodeHelper();
		$oNew->name = &$sName;
		$oNew->attributes = &$aAttributes;
		$oNew->parentNode = &$oParent;
		$nodeIdx = count($oParent->childNodes);
		$oParent->childNodes[$nodeIdx] = &$oNew;
	}

	public function FindChildNode(&$oStart, $element)
	{
		unset($this->currentNode);
		$this->currentNode = NULL;
		for ($i = 0; $i < count($oStart->childNodes) && $this->currentNode == NULL; $i++)
		{
			if ($oStart->childNodes[$i]->name == $element)
			{
				$this->currentNode = &$oStart->childNodes[$i];
			}
			else
			{
				$this->FindChildNode($oStart->childNodes[$i], $element);
				if ($this->currentNode != NULL && $this->currentNode->name == $element)
					return;
			}
		}
	}

	public function ListNodes(&$oStart, $element, $attribute, $value)
	{
		if ($oStart->name == $element && IsSet($oStart->attributes[$attribute]) && ($oStart->attributes[$attribute] == $value || $value == "*"))
			$this->nodes[] = &$oStart;

		for ($i = 0; $i < count($oStart->childNodes); $i++)
			$this->ListNodes($oStart->childNodes[$i], $element, $attribute, $value);
	}

	public function ClearList()
	{
		$this->nodes = array();
	}

	public function StartElement($parser, $name, $attributes)
	{
		if ($this->root == NULL)
		{
			$this->root = new XmlNodeHelper();
			$this->root->name = $name;
			$this->root->attributes = $attributes;
			$this->currentNode = &$this->root;
			return;
		}

		if ($this->currentNode == NULL)
			return;

		// Add new node and set it to be current node
		$this->AddChildNode($this->currentNode, $name, $attributes);
		$this->currentNode = &$this->currentNode->childNodes[count($this->currentNode->childNodes) - 1];
	}

	public function EndElement($parser, $name)
	{
		// Get rid of extra junk in data, if any
		$this->currentNode->data = trim($this->currentNode->data);

		// pop current node up the tree
		if ($this->currentNode->parentNode != NULL)
		{
			$parent = &$this->currentNode->parentNode;
			$this->currentNode = &$parent;
			return;
		}

		unset($this->currentNode);
		$this->currentNode = NULL;
	}

	public function DataElement($parser, $data)
	{
		$this->currentNode->data .= $data;
	}

	public function RenderNode(&$oNode)
	{
		// Opening tag
		$sNode = '<' . $oNode->name;
		if (count($oNode->attributes) > 0)
		{
			foreach ($oNode->attributes as $k => $v)
				$sNode .= ' ' . $k . '="' . $v . '"';
		}

		if (count($oNode->childNodes) == 0 && $oNode->data == '')
		{
			return $sNode . ' />';
		}

		$sNode .= '>';

		$sNode .= $oNode->data;

		// Children
		for ($i = 0; $i < count($oNode->childNodes); $i++)
			$sNode .= $this->RenderNode($oNode->childNodes[$i]);

		// Close Tag
		return $sNode . '</' . $oNode->name . '>';
	}

	public function ToXML()
	{
		$retVal = '<?xml version="1.0" ?>' . phpCrLf;

		return $retVal . $this->RenderNode($this->root);
	}

	public function ToFile($sFileName)
	{
		if (!($fp = fopen($sFileName, 'w+')))
			throw new NullReferenceException(sprintf(STR_CMMN_FILEOPENERR, $sFileName));

		if (!fwrite($fp, $this->ToXML()))
			throw new InvalidDataException(sprintf('Could not write to file %s', $sFileName));

		fclose($fp);

		return true;
	}
}
