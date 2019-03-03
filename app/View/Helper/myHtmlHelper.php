<?php
class myHtmlHelper extends AppHelper{
	public $helpers = array( 'Html' );
	public $icons = array(
		'upload'	=>	'fa-upload',
		'edit'		=>	'fa-pencil',
		'view'		=>	'fa-eye',
		'delete'	=>	'fa-times',
		'ship'		=>	'fa-truck',
		'file'		=>	'fa-file',
        'file-text' =>  'fa-file-text-o',
		'sad'		=>	'fa-frown-o',
		'barcode'	=>	'fa-barcode',
		'external'	=>	'fa-external-link',
		'plus'		=>	'fa-plus',
		'minus'		=>	'fa-minus',
		'picture'	=>	'fa-picture-o',
		'folder'	=>	'fa-folder-open',
		'settings'	=>	'fa-cogs',
		'list'		=>	'fa-list-ul',
		'heart'		=>	'fa-heart',
		'printer'	=>	'fa-print',
		'money'		=>	'fa-money',
		'star'		=>	'fa-star',
		'box'		=>	'fa-dropbox',
		'comment'	=>	'fa-comment',
		'dollar'	=>	'fa-usd',
		'ban'		=>	'fa-ban',
		'check'		=>	'fa-check',
		'invoice'	=>	'fa-file-text-o',
		'refresh'	=>	'fa-refresh',
		'spinner'	=>	'fa-spinner',
		'globe'		=>	'fa-globe',
		'twitter'	=>	'fa-twitter',
		'pinterest'	=>	'fa-pinterest',
		'email'		=>	'fa-envelope',
		'info'		=>	'fa-info-circle',
		'question'	=>	'fa-question-circle',
		'tags'		=>	'fa-tags',
		'save'		=>	'fa-floppy-o',
		'undo'		=>	'fa-undo',
		'lock'		=>  'fa-lock',
		'linkedin'  =>  'fa-linkedin',
		'googleplus'=>	'fa-google-plus',
		'facebook'  =>	'fa-facebook',
		'twitter'   =>  'fa-twitter',
		'key'		=>  'fa-key',
        'search'    =>  'fa-search',
        'download'  =>  'fa-download '
	);

	public function icon( $args ){
		if( !isset( $args[ 'type' ] ) or !$args[ 'type' ] ){
			return '';
		}
		
		$title = ( isset( $args[ 'title' ] ) ) ? ' ' . $args[ 'title' ] : '';

		if( !isset( $args[ 'toolTip' ] ) or !$args[ 'toolTip' ] ){
			$args[ 'toolTip' ] = '';
		}
		
		if( !isset( $args[ 'size' ] ) or !$args[ 'size' ] ){
			$args[ 'size' ] = '';
		}
		else{
			$args[ 'size' ] = 'fa-' . $args[ 'size' ];
		}
		
		if( !isset( $args[ 'border' ] ) or !$args[ 'border' ] ){
			$args[ 'border' ] = false;
		}
		else{
			$args[ 'border' ] = 'fa-border';
		}
		
		if( !isset( $args[ 'spin' ] ) or !$args[ 'spin' ] ){
			$args[ 'spin' ] = false;
		}
		else{
			$args[ 'spin' ] = 'fa-spin';
		}
		
		if( !isset( $args[ 'linkTarget' ] ) or !$args[ 'linkTarget' ] ){
			$args[ 'linkTarget' ] = '';
		}
		
		$icon = "<i class='fa " .
            $this->icons[ $args[ 'type' ] ] .
            $args[ 'spin' ] . ' ' .
            $args[ 'size' ] . ' ' .
            $args[ 'border' ] . "' ";

        if($args['toolTip']){
            $icon .= "data-toggle='tooltip' data-html='true' data-placement='left' data-original-title='" . h($args['toolTip']) . "' rel='tooltip'";
        }

        $icon .= "></i>";
        if($title){
            $icon .= '&nbsp;' . $title;
        }

		if(isset($args['args'])){
			$icon = $this->Html->link(
				$icon, 
				$args[ 'args' ], 
				array( 
					'escape' => false, 
					'target' => $args['linkTarget'],
                    'style' => (isset($args['style']))?$args['style']:''
				)
			);
        }
		
		return $icon;
	}

	public function button( $args ){		
		$title = ( isset( $args[ 'title' ] ) ) ? ' ' . $args[ 'title' ] : '';
		
		if( isset( $args[ 'icon' ] ) ){
			$args[ 'icon' ][ 'title' ] = $title;
			$title = $this->icon( $args[ 'icon' ] );
		}
		
		if( !isset( $args[ 'type' ] ) or !$args[ 'type' ] ){
			$args[ 'type' ] = 'primary';
		}	
		
		if( !isset( $args[ 'linkTarget' ] ) or !$args[ 'linkTarget' ] ){
			$args[ 'linkTarget' ] = '';
		}

        if( !isset( $args[ 'id' ] ) or !$args[ 'id' ] ){
            $args['id'] = '';
        }

        if( !isset( $args[ 'style' ] ) or !$args[ 'style' ] ){
            $args['style'] = '';
        }

        if( !isset( $args[ 'class' ] ) or !$args[ 'class' ] ){
            $args['class'] = '';
        }
		
		if( !isset( $args[ 'size' ] ) or !$args[ 'size' ] ){
			$args[ 'size' ] = '';
		}
		else{
			$args[ 'size' ] = 'btn-' . $args[ 'size' ];
		}
		
		return $this->Html->link( 
			$title, 
			$args['args'],
			array(
                'id' => $args['id'],
				'escape' => false, 
				'class' => 'btn btn-' . $args[ 'type' ] . ' ' . $args[ 'size' ] . ' ' . $args['class'],
				'target' => $args[ 'linkTarget' ] ,
                'style' => $args['style']
			) 
		);
	}
	
	public function image( $args ){
		if(!isset($args['args'])){
			$args['args'] = array();
		}
		
		if(Configure::read('useFtpForStorage')){
			$args['args']['fullBase'] = true;
		}
		
		return $this->Html->image($args['image'], $args['args']);
	}

    public function imageLogo( $image ){
        $size = array(
            'width' => '50%',
            'height' => '50%'
        );

        return $this->Html->image($image, $size);
    }
};