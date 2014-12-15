<?php

/**
 * @copyright	© 2010 JiaThis Inc.
 * @author		JiaThis [http://www.jiathis.com]
 * @since		version - 2010-10-20
 */

class plugin_jiathis {
    function plugin_jiathis() {
        global $_G;
        $this->script = $_G['cache']['plugin']['jiathis']['jiathis_script'];
		$this->pos_post = $_G['cache']['plugin']['jiathis']['jiathis_pos_post'];
		$this->pos_blog = $_G['cache']['plugin']['jiathis']['jiathis_pos_blog'];
    }
	
	function ie_version() {
		$match=preg_match('/MSIE ([0-9]\.[0-9])/',$_SERVER['HTTP_USER_AGENT'],$reg);
		if ($match == 0)
			return 999;
		else
			return floatval($reg[1]);
	}
	
	function is_ff() {
		$match=preg_match('/Firefox/',$_SERVER['HTTP_USER_AGENT'],$reg);
		if ($match == 0)
			return false;
		else
			return true;
	}
	
	function is_world() {
		$match=preg_match('/TheWorld/',$_SERVER['HTTP_USER_AGENT'],$reg);
		if ($match == 0)
			return false;
		else
			return true;
	}

}

class plugin_jiathis_group extends plugin_jiathis {
	function viewthread_postheader() {
		
		if ($this->pos_post == 1) {
			$v = $this->ie_version();
			if ($v <= 6) {
				return array('<div style="float:right;position:relative;top:-16px;">' . $this->script . '</div>');
			} else if ($v == 7) {
				return array('<div style="float:right;position:relative;top:-32px;">' . $this->script . '</div>');
			}else if ($this->is_ff()) {
				return array('<div style="float:right;">' . $this->script . '</div>');
			}else{
				return array('<div style="float:right;position:relative;top:6px;">' . $this->script . '</div>');
			}
		} else {
			return array('');
		}
	}
	function viewthread_posttop() {
		if ($this->pos_post == 2) {
			return array('<div style="margin:10px 0 10px 0;">' . $this->script . '</div><div id="JIATHIS_CODE_HTML1">');
		} else {
			return array('<div id="JIATHIS_CODE_HTML2">');
		}
	}
	function viewthread_postbottom() {
		if ($this->pos_post == 3 || $this->pos_post == 4) {
			return array('</div><div style="margin:10px 0 10px 0;">' . $this->script . '</div>');
		} else {
			return array('</div>');
		}
	}

	function viewthread_useraction() {
		return "";
	}
}

class plugin_jiathis_forum extends plugin_jiathis {
	function viewthread_postheader() {
		if ($this->pos_post == 1) {
			$v = $this->ie_version();
			if ($v <= 6) {
				return array('<div style="float:right;position:relative;top:-14px;">' . $this->script . '</div>');
			} else if ($v == 7) {
				return array('<div style="float:right;position:relative;top:-19px;">' . $this->script . '</div>');
			}else if($this->is_world()){
				return array('<div style="float:right;position:relative;top:-19px;">' . $this->script . '</div>');
			}else if ($this->is_ff()) {
				return array('<div style="float:right;">' . $this->script . '</div>');
			} else {
				return array('<div style="float:right;position:relative;top:0px;">' . $this->script . '</div>');
			}
		} else {
			return array();
		}
	}
	function viewthread_posttop() {
		if ($this->pos_post == 2) {
			return array('<div style="padding-top:5px;margin-bottom:25px;">' . $this->script . '</div><div id="JIATHIS_CODE_HTML3">');
		} else {
			return array('<div id="JIATHIS_CODE_HTML4">');
		}
	}
	function viewthread_postbottom() {
		if ($this->pos_post == 3 || $this->pos_post == 4) {
			return array('</div><div style="padding-top:10px;margin-bottom:10px;">' . $this->script . '</div>');
		} else {
			return array('</div>');
		}
	}

	function viewthread_useraction() {
		return "";
	}
}

class plugin_jiathis_home extends plugin_jiathis {
	function space_blog_title() {
		if ($this->pos_blog == 1) {
			return '<div style="padding:10px 0 15px 0;">' . $this->script . '</div>';
		}
	}
	function space_blog_op_extra() {
		if ($this->pos_blog  == 2) {
			return '<div style="float:left;">' . $this->script . '</div>';
		}
	}
}
?>