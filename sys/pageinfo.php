<?php

class Pageinfo{
 	
	private function Pageinfo( $url_datas ){
		$this->params = $url_datas;
		$pagetype = array_shift( $url_datas );
		$this->pagetype = ( empty( $pagetype ) ||  ( strpos( $pagetype , "?" ) !== false ) ) ? 'index' : $pagetype;
		
		if( !empty( $url_datas ) )
			foreach( $url_datas as $arg )
				$this->pageargs[] = $arg;
		else
			$this->pageargs[] = null;	
	}
	
	
	public static function getInstance( $url_datas ){
		global $arpt_pageinfo;
		if( is_null($arpt_pageinfo) )
			return new Pageinfo( $url_datas );
		return $arpt_pageinfo;
	}
	
	public function set_pagetype( $page ){
		$this->pagetype = $page;
	}
	
	public function get_pagetype(){
		return $this->pagetype;
	}

	public function get_params(){
		return $this->params;
	}
	
	public function get_pageargs( $c = null){
		if( is_null( $c ) ) :
			return remove_params( $this->pageargs );
		endif;
		if( isset( $this->pageargs[$c] ) )
			return remove_params( $this->pageargs[$c] );
		return false;
	}

	public function get_lastarg(){
		return end( $this->params );
	}

	public function seo(){
		if( is_contentpage() || is_categorypage() ) :
			qnext();
			$this->pagetitle = qtitle() . ' - ' . sitename();
			$this->pagedescr =  strip_tags( substr( qcontent() , 0 , 200 ) ) . ' - ' . sitename() . ' - ' . description();
			qreset();
		elseif( is_homepage() ) :
			$this->pagetitle = sitename();
			if( $slogan = siteslogan() )
				$this->pagetitle .= ', ' . lcfirst( $slogan );
			$this->pagedescr = description() . ' - ' . sitename();
		elseif( is_searchpage() ) :
			$this->pagetitle = 'Page de recherche - ' . sitename();
			$this->pagedescr = 'Recherche sur ' . last_value('search') . ' - ' . sitename();
		elseif( is_archivepage() ) :
			$this->pagetitle = 'Page d\'archive - ' . sitename();
			$this->pagedescr = 'Archive - ' . get_pagetype() . ' - ' . sitename();
		elseif( is_keywordspage() ) :
			$this->pagetitle = 'Page des mots clés - ' . sitename();
			$this->pagedescr = 'Mots clés - ' . get_pageargs(0) . ' - ' . sitename();
		elseif( is_errorpage() ) :
			$this->pagetitle = 'Erreur 404 : Page introuvable - ' . sitename();
			$this->pagedescr = description() . ' - ' . sitename();
		endif;

		$this->pagetitle = call_layers('seo_pagetitle_layer',$this->pagetitle,get_pagetype());
		$this->pagedescr = call_layers('seo_pagedescr_layer',$this->pagedescr,get_pagetype());
	}

	public function get_pagetitle(){
		return $this->pagetitle;
	}

	public function get_pagedescr(){
		return $this->pagedescr;
	}


}