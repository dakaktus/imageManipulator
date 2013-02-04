
    class imageManipulator {
	private $_file = null;
	private $_image = null;
	private $_imageData = array();

	public function setFilename($filename){
	    $this->_file = $filename;
	}
	
	public function trimWhiteMargins($r,$g,$b){
	    $horizontalMargins = $this->calculateHorizontalMargin($r,$g,$b);
	    $verticalMargins = $this->calculateWerticalMargin($r,$g,$b);
	    $newImage = imagecreatetruecolor(($horizontalMargins['right']-$horizontalMargins['left']), ($verticalMargins['bottom']-$verticalMargins['top']));
	    $color = imagecolorallocate($newImage, 255, 255, 255);
	    imagefill($newImage, 0, 0, $color);
	    imagecopy($newImage, $this->getImage(), 0, 0, $horizontalMargins['left'], $verticalMargins['top'], ($horizontalMargins['right']-$horizontalMargins['left']), ($verticalMargins['bottom']-$verticalMargins['top']));
	    $this->_image = $newImage;
	}
	
	public function saveImage($path,$filename,$extension){
	    switch($extension){
		case 'png':
		    imagepng($this->getImage(), $path.$filename.'.'.$extension);
		    break;
		case 'jpeg':
		case 'jpg':
		    imagejpeg($this->getImage(), $path.$filename.'.'.$extension);
		    break;
		case 'gif':
		    imagejpeg($this->getImage(), $path.$filename.'.'.$extension);
		    break;
	    }
	}
	
	private function standarizeToNonalpha(){
	    $newImage = imagecreatetruecolor($this->getImageWidth(), $this->getImageHeight());
	    $white = imagecolorallocate($newImage, 255, 255, 255);
	    imagefill($newImage, 0, 0, $white);
	    imagecopy($newImage, $this->getImage(), 0, 0, 0, 0, $this->getImageWidth(), $this->getImageHeight());
	    $this->_image = $newImage;
	}
	
	private function getImageExtension(){
	    $extension = explode('.',$this->_file);
	    return end($extension);
	}
	
	private function getImage(){
	    if ($this->_image === null){
		switch ($this->getImageExtension()){
		    case 'png':
			$this->_image = imagecreatefrompng($this->_file);
			break;
		    case 'jpg':
		    case 'jpeg':
			$this->_image = imagecreatefromjpeg($this->_file);
			break;
		    case 'gif':
			$this->_image = imagecreatefromgif($this->_file);
			break;
		    case 'bmp':
			$this->_image = imagecreatefromwbmp($this->_file);
			break;
		}
	    }
	    return $this->_image;
	}
	
	private function getImageWidth(){
	    if ($this->_imageData['width'] === null){
		$this->_imageData['width'] = imagesx($this->getImage());
	    }
	    return $this->_imageData['width'];
	}
	
	private function getImageHeight(){
	    if ($this->_imageData['height'] === null){
		$this->_imageData['height'] = imagesy($this->getImage());
	    }
	    return $this->_imageData['height'];
	}
	
	private function calculateWerticalMargin($r = 0,$g = 0,$b = 0){
	    $top = 0;
	    $bottom = $this->getImageHeight();
	    for ($y = 0;$y<$this->getImageHeight();$y++){
		$onlyWhite = true;
		for ($x=0;$x<$this->getImageWidth();$x++){
		    $parameters = imagecolorsforindex($this->getImage(),imagecolorat($this->getImage(), $x, $y));
		    if (((($parameters['red']!=$r)&&($parameters['green']!=$g)&&($parameters['blue']!=$b)))&&($parameters['alpha']!=127 && $r==255 && $g == 255 && $b == 255)){
			$onlyWhite = false;
			break;
		    };
		}
		if ($onlyWhite === false){
		    $top = $y;
		    break;
		}
	    }
	    for ($y = $this->getImageHeight()-1;$y>=0;$y--){
		$onlyWhite = true;
		for ($x=0;$x<$this->getImageWidth();$x++){
		    $parameters = imagecolorsforindex($this->getImage(),imagecolorat($this->getImage(), $x, $y));
		    if (((($parameters['red']!=$r)&&($parameters['green']!=$g)&&($parameters['blue']!=$b)))&&($parameters['alpha']!=127 && $r==255 && $g == 255 && $b == 255)){
			$onlyWhite = false;
			break;
		    };
		}
		if ($onlyWhite === false){
		    $bottom = $y;
		    break;
		}
	    }
	    return array('top'=>$top,'bottom'=>$bottom);
	}
	
	private function calculateHorizontalMargin($r = 0,$g = 0,$b = 0){
	    $left = 0;
	    $right = $this->getImageWidth();
	    for ($x = 0;$x<$this->getImageWidth();$x++){
		$onlyWhite = true;
		for ($y = 0;$y<$this->getImageHeight();$y++){
		    $parameters = imagecolorsforindex($this->getImage(),imagecolorat($this->getImage(), $x, $y));
		    if (((($parameters['red']!=$r)&&($parameters['green']!=$g)&&($parameters['blue']!=$b)))&&($parameters['alpha']!=127 && $r==255 && $g == 255 && $b == 255)){
			$onlyWhite = false;
			break;
		    };
		}
		if ($onlyWhite === false){
		    $left = $x;
		    break;
		}
	    }
	    for ($x = $this->getImageWidth()-1;$x>=0;$x--){
		$onlyWhite = true;
		for ($y = 0;$y<$this->getImageHeight();$y++){
		    $parameters = imagecolorsforindex($this->getImage(),imagecolorat($this->getImage(), $x, $y));
		    if (((($parameters['red']!=$r)&&($parameters['green']!=$g)&&($parameters['blue']!=$b)))&&($parameters['alpha']!=127 && $r==255 && $g == 255 && $b == 255)){
			$onlyWhite = false;
			break;
		    };
		}
		if ($onlyWhite === false){
		    $right = $x;
		    break;
		}
	    }
	    return array('left'=>$left,'right'=>$right);
	}
    
    };
