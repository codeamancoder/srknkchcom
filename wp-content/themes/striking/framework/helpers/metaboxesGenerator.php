<?php

/**
 * The `metaboxesGenerator` class help generate the html code for meta boxes.
 */
class metaboxesGenerator{
	var $config;
	var $options;
	var $saved_options;
	var $generator;
	
	/**
	 * Constructor
	 * 
	 * @param string $name
	 * @param array $options
	 */
	function metaboxesGenerator($config, $options) {
		include_once (THEME_HELPERS . '/baseOptionsGenerator.php');
		$this->generator = new baseOptionsGenerator();
		
		$this->config = $config;
		$this->options = $options;
		
		add_action('admin_menu', array(&$this, 'create'));
		add_action('save_post', array(&$this, 'save'));
	}
	
	function create() {
		if (function_exists('add_meta_box')) {
			if (! empty($this->config['callback']) && function_exists($this->config['callback'])) {
				$callback = $this->config['callback'];
			} else {
				$callback = array(&$this, 'render');
			}
			if(is_array($this->config['pages'])){
				foreach($this->config['pages'] as $page) {
					add_meta_box($this->config['id'], $this->config['title'], $callback, $page, $this->config['context'], $this->config['priority']);
				}
			}
		}
	}
	
	function save($post_id) {
		if (! isset($_POST[$this->config['id'] . '_noncename'])) {
			return $post_id;
		}
		
		if (! wp_verify_nonce($_POST[$this->config['id'] . '_noncename'], plugin_basename(__FILE__))) {
			return $post_id;
		}
		
		if ('page' == $_POST['post_type']) {
			if (! current_user_can('edit_page', $post_id)) {
				return $post_id;
			}
		} else {
			if (! current_user_can('edit_post', $post_id)) {
				return $post_id;
			}
		}
		
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
			return $post_id;
		}
		foreach($this->options as $option) {
			if (isset($option['id']) && ! empty($option['id'])) {
				
				if (isset($_POST[$option['id']])) {
					switch ($option['type']) {
						case 'multiselect':
							if(isset($option['chosen_order']) && $option['chosen_order']){
								if(empty($_POST['_'.$option['id']])){
									$value = array();
								}else{
									$value = explode(",",$_POST['_'.$option['id']]);
								}
							}else{
								if(empty($_POST[$option['id']])){
									$value = array();
								}else{
									$value = $_POST[$option['id']];
								}
							}							
							break;
						case 'color':
							if(!empty($_POST[$option['id']]) && $_POST[$option['id']]=='transparent'){
								$value = '';
							}else{
								$value = $_POST[$option['id']];
							}
							break;
						case 'multidropdown':
							$value = array_unique(explode(',', $_POST[$option['id']]));
							break;
						case 'tritoggle':
							switch($_POST[$option['id']]){
								case 'true':
									$value = 'true';
									break;
								case 'false':
									$value = 'false';
									break;
								case 'default':
									$value = '';
							}
							break;
						case 'toggle':
							$value = 'true';
							break;
						case 'upload':
							if(!empty($_POST[$option['id']])){
								$value = (array) json_decode(stripslashes($_POST[$option['id']]),true);
							}else{
								$value = array();
							}
							break;
						default:
							$value = $_POST[$option['id']];
					}
				} else if ($option['type'] == 'toggle') {
					$value = 'false';
				} else {
					$value = false;
				}
				
				if (isset($option['process']) && function_exists($option['process'])) {
					$value = $option['process']($option,$value);
				}
				
				if (get_post_meta($post_id, $option['id']) == "") {
					add_post_meta($post_id, $option['id'], $value, true);
				} elseif ($value != get_post_meta($post_id, $option['id'], true)) {
					update_post_meta($post_id, $option['id'], $value);
				} elseif ($value == "") {
					delete_post_meta($post_id, $option['id'], get_post_meta($post_id, $option['id'], true));
				}
			}
		}
	}
	
	function render() {
		foreach($this->options as $option) {
			$this->renderOption($option);
		}
		
		echo '<input type="hidden" name="' . $this->config['id'] . '_noncename" id="' . $this->config['id'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
	}
	
	function renderOption($option){
		global $post;
		if (isset($option['id'])) {
			$value = get_post_meta($post->ID, $option['id'], true);
			if ($value != "") {
				$option['value'] = $value;
			}else{
				$option['value'] = $option['default'];
			}
		}
		if (isset($option['prepare']) && function_exists($option['prepare'])) {
			$option = $option['prepare']($option);
		}
		if (method_exists($this->generator, $option['type'])) { 
			echo '<div class="meta-box-item">';
			echo '<div class="meta-box-item-title"><h4>' . $option['name'] . '</h4>';
			if (isset($option['desc'])) {
				echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $option['desc'] . '</p>';
			} else {
				echo '</div>';
			}
			echo '<div class="meta-box-item-content">';
			$this->generator->$option['type']($option);
			echo '</div>';
			echo '</div>';
		}elseif (method_exists($this, $option['type'])) {
			$this->$option['type']($option);
		}
	}
	
	/**
	 * prints the title and desc
	 */
	function title($item) {
		echo '<div class="meta-box-item">';
		if (isset($item['name'])) {
			echo '<div class="meta-box-item-title"><h4>' . $item['name'] . '</h4></div>';
		}
		if (isset($item['desc'])) {
			echo '<p>' . $item['desc'] . '</p>';
		}
		echo '</div>';
	}
	
	/**
	 * displays a custom field
	 */
	function custom($item) {
		if(isset($item['layout']) && $item['layout']==false){
			if (isset($item['function']) && function_exists($item['function'])) {
				if(isset($item['default'])){
					$item['function']($item, $item['default']);
				}else{
					$item['function']($item);
				}
			} else {
				echo $item['html'];
			}
		}else{
			echo '<div class="meta-box-item">';
			echo '<div class="meta-box-item-title"><h4>' . $item['name'] . '</h4>';
			if (isset($item['desc'])) {
				echo '<a class="switch" href="">[+] more info</a></div><p class="description">' . $item['desc'] . '</p>';
			} else {
				echo '</div>';
			}
			echo '<div class="meta-box-item-content">';
		
			if (isset($item['function']) && function_exists($item['function'])) {
				if(isset($item['default'])){
					$item['function']($item, $item['default']);
				}else{
					$item['function']($item);
				}
			} else {
				echo $item['html'];
			}
			echo '</div>';
			echo '</div>';
		}
	}
	
	function group_start($item){
		echo '<div class="meta-box-group"';
		if(isset($item['group'])){
			echo ' id="'.$item['group'].'"';
		}
		echo '>';
	}
	
	function group_end($item){
		echo '</div>';
	}
}
