<?php

class Contents extends Queries{

	public function __construct( $specificities = null , $action = 'select' ){ 
		if( !is_array( $specificities ) ) :
			$tmp = $specificities;
			unset( $specificities );
			$specificities['id'] = $tmp;
		endif;
		if( $action == 'select' ) :


			$clean = array( 	'selection' => '*',
								'title' => null,
								'type' 	=> null,
								'id'	=> null,
								'userid' => null,
								'parent_id'	=> null,
								'slug'	=> null,
								'status' => 'public',
								'orderby' => 1,
								'ob_suffix' => 'DESC',
								'limit'	=> 'nolimit',
								'offset' => 0,
								'specified_ids' => null,
								'year' => null,
								'month' => null,
								'day' => null,
								'search' => null,
								'category' => null
								 );
			$specificities = array_merge( $clean , $specificities);

			$specificities = $this->internal_process( $specificities );

			$this->specificities = $specificities;

			$this->currentpage = currentpage();
			$this->limit = $specificities['limit'];

			$where = ' 1 = 1  ' .clause_where( 'id' , '=' , $specificities['id'] , ' AND ' ) . clause_where( 'content_type' , '=' , $specificities['type'] , ' AND ' , clause_where( 'parent_id', '=' , $specificities['parent_id'] , ' AND ' ) ) . clause_where( 'content_slug' , '=' , $specificities['slug'] , ' AND ' ) . clause_where( 'user_id' , '=' , $specificities['userid'] , ' AND ' ) . clause_where( 'content_status' , '=' , $specificities['status'] , ' AND ' ) . clause_where( 'content_title' , '=' , $specificities['title'] , ' AND ' );
			$where .= clause_in( 'id' , $specificities['specified_ids'] , ' AND ');
			$where .= clause_where('YEAR(content_date)' , '=' , $specificities['year'] , ' AND ' ) . clause_where( 'MONTH(content_date)' , '=' , $specificities['month'] , ' AND ' ) . clause_where( 'DAY(content_date)' , '=' , $specificities['day'] , ' AND ' );
			$where .= clause_orderby( $specificities['orderby'] , '' , '' , '' , ' ' . $specificities['ob_suffix'] . ' ');				
			$where .= clause_limit( $specificities['limit'] , ($specificities['offset'])*$specificities['limit'] );
		
			$specificities = array( 'where' => $where , 'selection' => $specificities['selection'] );
		//	endif;
		//	
			
		//logr($where);
		elseif( $action == 'update' ) :
			$default = array(
				'id' => null,
				'parent_id' => null,
				'content_model' => null,
				'title' => null,
				'slug' => null,
				'message' => null );

			$specificities = array_merge( $default , $specificities );

			$query['set'] = clause_where( 'id' , '=' , $specificities['id'] ) . clause_where( 'parent_id' , '=' , $specificities['parent_id'] , ',' ) . clause_where( 'content_model' , '=' , $specificities['content_model'] , ',' ) . clause_where( 'content_title' , '=' , $specificities['title'] , ',' ) . clause_where( 'content_slug' , '=' , $specificities['slug'] , ',' ) . clause_where( 'content_content' , '=' , $specificities['message'] , ',' );
			$query['where'] = clause_where( 'id' , '=' , $specificities['id'] );

			$specificities = $query;


		elseif( $action == 'delete' ) :
			if( is_array( $specificities ) )
				$id = $specificities['id'];
			else
				$id = $specificities;

			unset( $specificities );

			$specificities['where'] = clause_where( 'id' , '=' , $id );
		endif;
		parent::__construct($action,'arpt_contents',$specificities);
	}

	private function internal_process( $specificities ){
		if( $specificities['status'] == 'all' )
			$specificities['status'] = null;

		if( $specificities['category'] != null ) :
			if( $specificities['slug'] == '' ){
				$specificities['specified_ids'] = get_contentsids_by_categoryid( get_catid_by_name( $specificities['category'] ));
				//$specificities['category'] = null;
			}else{			
				$catid = get_content_category( get_contentid( $specificities['slug'] ) );
				$name = get_category_by_id( $catid , 'name' );
				if(  strtolower( $name ) != strtolower( get_pagetype() ) ) :
					$specificities['slug'] = 'SlugDoesNotCorrespondWithCategory';
					//$specificities['category'] = null;
					return $specificities;
				endif;

				//$specificities['specified_ids'] = get_contentsids_by_categoryid(  get_catid_by_name( $specificities['category'] ) );
			}

			/*
			if( !filter_exists( get_pageargs(0) ) ) :
				$specificities['slug'] = null;
				$specificities['specified_ids'] = get_contentsids_by_categoryid( get_catid_by_name( $specificities['category'] ));
				$specificities['category'] = null;
				return $specificities;
			endif;
			
			$catid = get_content_category( get_contentid( $specificities['slug'] ) );
			$name = get_category_by_id( $catid , 'name' );
			if(  strtolower( $name ) != strtolower( get_pagetype() ) ) :
				$specificities['slug'] = 'SlugDoesNotCorrespondWithCategory';
				$specificities['category'] = null;
				return $specificities;
			endif;

			$specificities['specified_ids'] = get_contentsids_by_categoryid(  get_catid_by_name( $specificities['category'] ) );

			//logr($specificities);*/
		endif;

		if( $specificities['search'] != null ) : 
			$search = singularize( do_slug( $specificities['search'] ) );
			$ids = array();
			$r = get_contents( array( 'slug' => 'LIKE-'. $search , 'selection' => 'id' ) );

			//logr($r->getAll());
			foreach( $r->getAll() as $entity )
				$ids[] = $entity['id'];

			$tmp_search = str_replace( '-' , ' ' , $specificities['search'] );

			$words = array_filter( explode( ' ' ,  $tmp_search ) );
			if( count( $words ) > 1 ){
				$longestWord = singularize( do_slug( longestWord( $tmp_search ) ) );
				$r = get_contents( array( 'slug' => 'LIKE-'. $longestWord , 'selection' => 'id' ) );

				foreach( $r->getAll() as $entity )
					$ids[] = $entity['id'];
			}

			if( !($ids_by_tag = get_ids_by_tag( $search ) ) )
				$ids_by_tag = array( '0' );

			$specificities['specified_ids'] = array_merge( $ids , $ids_by_tag );
			$specificities['search'] = null;

		endif;


		return $specificities;
	}

	public function pagination( $extension = 3 ){
		$cp = $this->currentpage;

		$mirror = $this->new_based_on_current( array( 'limit' => 'nolimit' ) );
		$max =  ceil( $mirror->total / $this->limit );

		$this->paginate( $extension , $cp , $max );
	}

	public function new_based_on_current( $args ){
		return new Contents( array_merge( $this->specificities , $args ) );
	}

	public function related_contents(){
		if( !$this->qhas() ) return false;
		$tags = $this->qtag( 'array' );
		foreach( $tags as $tag )
			$real_tags[] = $tag['name'];

		if( !isset($real_tags) ) return;

		$other_contents = new Contents( array( 'id' => 'NOT-' . $this->qid() , 'type' => $this->qtype() ) );

		$count = array();
		$temp_count = 0;

		if( $other_contents->has() ) :
			while( $other_contents->next() ) :
				$other_contents_tags = $other_contents->qtag( 'array' );
				foreach( $other_contents_tags as $tag ) :
					if( in_array( $tag['name'] , $real_tags ) )
						$temp_count++;
				endforeach;

				if( $temp_count > 0 )
					$count[$other_contents->qid()] = $temp_count;
				$temp_count = 0;
			endwhile;

			arsort( $count );		

			if( ( $limit = sizeof( $count ) ) > 3 )
				$limit = 3;
		
			return array_slice( $count , 0 , $limit , true );
		else :
			return array();
		endif;
	}

	public function qminiature( $formatname = 'classic' ){
		if( !$this->qhas() ) return false;
		return get_miniature( 'content' , $this->qid() );
	}
	public function qid(){
		if( !$this->qhas() ) return false;
		return $this->datas->id;
	}
	public function qpid(){
		if( !$this->qhas() ) return false;
		return $this->datas->parent_id;
	}
	public function qauthorid(){
		if( !$this->qhas() ) return false;
		return $this->datas->user_id;
	}
	
	public function qhas(){
		return $this->total > 0 ? true : false;
	}

	public function qauthorlink(){
		if( !$this->qhas() ) return false;
		$name = $this->qauthor();
		$slug = get_userslug( $this->qauthorid() );

		return a( get_url( 'author/' . $slug ) , $name );
	}
	public function qauthor(){
		if( !$this->qhas() ) return false;
		return get_username( $this->qauthorid() );
	}
	public function qlink(){
		if( !$this->qhas() ) return false;
		return content_link( $this->qid() );
	}
	public function qtitle(){
		if( !$this->qhas() ) return false;
		return $this->datas->content_title;
	}

	public function qtitlelink( $arg = null ){
		if( !$this->qhas() ) return false;
		return a( $this->qlink() , $this->qtitle() , $arg );
	}
	public function qcontent(){
		if( !$this->qhas() ) return false;
		return $this->datas->content_content;
	}
	public function qsumup(){
		if( !$this->qhas() ) return false;
		$sumup = preg_replace('/\s+?(\S+)?$/', '', substr( strip_tags($this->qcontent()) , 0 , call_layers( 'qsumup_size_layer' , 400 ) ));
		if( $sumup != '' )
			return $sumup .  '...';
		return '';
	}
	public function qnext(){
		if( !$this->qhas() ) return false;
		return $this->next();
	}
	public function qdate_notformated(){
		if( !$this->qhas() ) return false;
		return $this->datas->content_date;
	}
	public function qdate(){
		if( !$this->qhas() ) return false;
		return arpt_date( $this->datas->content_date );
	}
	public function qtype(){
		if( !$this->qhas() ) return false;
		return $this->datas->content_type;
	}
	public function qslug(){
		if( !$this->qhas() ) return false;
		return $this->datas->content_slug;
	}
	public function qstatus(){
		if( !$this->qhas() ) return false;
		return $this->datas->content_status;
	}
	public function qreset(){
		if( !$this->qhas() ) return false;
		return $this->reset();
	}

	public function qtag( $format = 'arpt' ){
		if( !$this->qhas() ) return false;
		if( $format == 'arpt' )
			return display_tag_list( $this->qid() );
		elseif( $format == 'array' )
			return get_tag_list( $this->qid() );
		else
			return;
	}
	public function qmodel(){
		if( !$this->qhas() ) return false;
		return $this->datas->content_model;
	}
	public function qcategory(){
		if( !$this->qhas() ) return false;
		return get_content_categoryname( $this->qid() );
	}
	public function qproperty( $name ){
		if( !$this->qhas() ) return false;
		return get_contentproperty( $this->qid() , $name );
	}
}