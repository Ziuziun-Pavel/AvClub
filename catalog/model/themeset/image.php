<?php
class ModelThemesetImage extends Model {

	public function original($filename, $width=100, $height=100, $type='') {
		return $this->image_resize($filename, $width, $height, 'original');
	}
	public function crop($filename, $width = 100, $height = 100, $type='') {
		return $this->image_resize($filename, $width, $height, 'crop');
	}
	public function resize($filename, $width, $height) {
		$this->load->model('tool/image');
		return $this->model_tool_image->resize($filename, $width, $height);
	}
	private function image_resize($filename, $width, $height, $type = 'crop') {

		if (!is_file(DIR_IMAGE . $filename)) {
			if (is_file(DIR_IMAGE . 'no_image.jpg')) {
				$filename = 'no_image.jpg';
			} elseif (is_file(DIR_IMAGE . 'no_image.png')) {
				$filename = 'no_image.png';
			} else {
				return;
			}
		}


		$extension = pathinfo($filename, PATHINFO_EXTENSION);

		if($extension === 'svg') {
			$image_old = $filename;
			$image_new = 'cache/' . $filename;

			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}
			
			if (!is_file(DIR_IMAGE . $image_new) || (filectime(DIR_IMAGE . $image_old) > filectime(DIR_IMAGE . $image_new))) {
				copy(DIR_IMAGE . $filename, DIR_IMAGE . $image_new);
			} 

			if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
				return $this->config->get('config_ssl') . 'image/' . $image_new;
			} else {
				return $this->config->get('config_url') . 'image/' . $image_new;
			}
		}

		$image_old = $filename;

		if($type === 'original') {
			list($width, $height, $image_type) = getimagesize(DIR_IMAGE . $image_old);
		}

		$image_new = 'cache/' . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . (int)$width . 'x' . (int)$height . '.' . $extension;


		if (!is_file(DIR_IMAGE . $image_new) || (filectime(DIR_IMAGE . $image_old) > filectime(DIR_IMAGE . $image_new))) {
			list($width_orig, $height_orig, $image_type) = getimagesize(DIR_IMAGE . $image_old);

			if (!in_array($image_type, array(IMAGETYPE_PNG, IMAGETYPE_JPEG, IMAGETYPE_GIF))) {
				return DIR_IMAGE . $image_old;
			}

			$path = '';

			$directories = explode('/', dirname($image_new));

			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;

				if (!is_dir(DIR_IMAGE . $path)) {
					@mkdir(DIR_IMAGE . $path, 0777);
				}
			}

			if ($width_orig != $width || $height_orig != $height) {
				$image = new Image(DIR_IMAGE . $image_old);

				$scaleW = $width_orig / $width;
				$scaleH = $height_orig / $height;

				if ($scaleH > $scaleW) {
					$_height = $height * $scaleW;

					$top_x = 0;
					$top_y = ($height_orig - $_height) / 2;

					$bottom_x = $width_orig;
					$bottom_y = $top_y + $_height;

					$image->crop($top_x, $top_y, $bottom_x, $bottom_y);
				} elseif ($scaleH < $scaleW) {
					$_width = $width * $scaleH;

					$top_x = ($width_orig - $_width) / 2;
					$top_y = 0;

					$bottom_x = $top_x + $_width;
					$bottom_y = $height_orig;

					$image->crop($top_x, $top_y, $bottom_x, $bottom_y);
				}

				$image->resize($width, $height);
				$image->save(DIR_IMAGE . $image_new, 100);
			} else {
				copy(DIR_IMAGE . $image_old, DIR_IMAGE . $image_new);
			}
		}

		$imagepath_parts = explode('/', $image_new);
		$new_image = implode('/', array_map('rawurlencode', $imagepath_parts));

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			return $this->config->get('config_ssl') . 'image/' . $new_image;
		} else {
			return $this->config->get('config_url') . 'image/' . $new_image;
		}
	}


}