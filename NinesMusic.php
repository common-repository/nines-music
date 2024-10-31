<?php
/*
Plugin Name: 网易云音乐
Plugin URI: https://wordpress.org/plugins/nines-music/
Description: 迷你，吸底型网易云音乐播放器
Version:1.6.2
Author: 不问归期_
Author URI: https://www.aliluv.cn/
*/
// 如果直接调用此文件，请中止。
if (!defined('WPINC')) {
	die;
}


require_once plugin_dir_path(__FILE__) . 'inc/classes/setup.class.php';

if (class_exists('CSF')) {
	// var_dump('sdf');
	$prefix = 'jiutu_musics_config';
	CSF::createOptions($prefix, array(
		'framework_title'   => '网站音乐' . '<small><a href="https://www.aliluv.cn" target="_blank">查看演示(左下角)</a></small>',
		'show_search'       => false,
		'theme'             => 'light',
		'menu_title'        => '网易云音乐',
		'menu_icon'         => 'dashicons-playlist-audio',
		'menu_slug'         => 'jiutu_music',
		'footer_text'       => '任何使用问题可联系QQ:781272314',
		'nav'               => 'inline',
		'show_reset_all'    => false,
		'show_reset_section' => false,
		'show_all_options'  => false,
		// 'menu_capability'   => 'subscriber',
		// 'menu_position'     => '60',
	));


	CSF::createSection($prefix, array(
		'title'  => '播放器设置',
		'fields' => array(

			array(
				'id'        => 'player_type',
				'type'      => 'image_select',
				'options'   => array(
					'aplayer' => plugins_url('/static/img/aplayer.png', __FILE__),
					'value-2' => plugins_url('/static/img/player.png', __FILE__),
				),
				'default'   => 'aplayer',
				'desc'        => '播放器类型!选择后会自动切换对应的播放器配置、自行配置功能即可',
			),
			array(
				'id'         => 'playback_type',
				'type'       => 'button_set',
				'title'      => '音乐播放类型',
				'options'    => array(
					'custom'  => '自定义音乐',
					'netease' => '网易云音乐',
					'tencent' => 'QQ音乐',
					'kugou' => '酷狗音乐',
				),
				'default'    => 'custom',
				'desc'        => '音乐数据需从 WP管理平台 获取、下方配置',
				'help'    => '除了 自定义音乐!其他播放类型支持自动更新歌单歌曲数据!但为了避免消耗服务器资源、服务器设置缓存(大概过一小时左右会刷新数据)'
			),

			array(
				'id'     => 'aplayer_settings',
				'type'   => 'fieldset',
				'dependency' => array('player_type', '==', 'aplayer'),
				'fields' => array(
					array(
						'id'         => 'aplayer_type',
						'type'       => 'button_set',
						'title'      => '播放器类型',
						'options'    => array(
							'side'  => '侧边模式',
							'bottom' => '底部模式',
						),
						'default'    => 'side',
						'desc'        => '侧边模式/<a href="https://www.aliluv.cn" target="_blank">演示地址(左下角)</a>  底部模式/<a href="https://wpapi.aliluv.cn" target="_blank">演示地址(底部)</a>',
					),
					/**
					 * 侧边模式
					 */
					array(
						'id'     => 'side',
						'type'   => 'fieldset',
						'dependency' => array('aplayer_type', '==', 'side'),
						'fields' => array(
							array(
								'id'    => 'fixed',
								'type'  => 'switcher',
								'title' => '1、吸底模式',
								'default' => true,
								'help' => '吸底固定播放器',
							),
							array(
								'id'    => 'mini',
								'type'  => 'switcher',
								'title' => '2、迷你模式',
								'default' => true,
							),

							array(
								'id'    => 'hide_picture',
								'type'  => 'switcher',
								'title' => '3、歌曲图片',
								'dependency' => array(array('mini', '==', 'true'), array('fixed', '==', 'true')),
								'help' => '必须开启(迷你模式 吸底模式)这俩功能才可用',
								'desc'  => '是否显示歌曲图片'
							),
							array(
								'id'          => 'memory',
								'type'        => 'switcher',
								'title'       => '4、记忆播放',
								'default'     => true,
								'help'        => '当前播放的歌曲、时长.播放器会缓存!刷新后可接着播放',
								'desc'        => '禁用:(刷新、打开)都会默认从第一首歌曲播放'
							),
							array(
								'id'          => 'show_lyrics',
								'type'        => 'switcher',
								'title'       => '5、歌词显示',
								'default'     => true,
								'help'        => '是否显示歌词',
								'desc'        => '默认打开页面是否显示歌词'
							),
							array(
								'id'    => 'autoplay',
								'type'  => 'switcher',
								'title' => '6、音频自动播放',
								'default' => false,
								'help' => 'PC端已知 谷歌浏览器 限制音频自动播放、移动端 大多数浏览器禁止了音频自动播放!',
							),
							array(
								'id'    => 'theme',
								'type'  => 'color',
								'title' => '7、主题色',
								'default' => '#b7daff'
							),
							array(
								'id'    => 'notice_switch',
								'type'  => 'switcher',
								'title' => '8、站点公告',
							),

							array(
								'id'      => 'notice',
								'type'    => 'textarea',
								'title'   => '9、公告/欢迎语',
								'default' => '欢迎访问!!',
								'dependency' => array('notice_switch', '==', 'true'),
							),
							array(
								'id'          => 'loop',
								'type'        => 'select',
								'title'       => '10、音频循环播放',
								'options'     => array(
									'all'  => '循环播放',
									'one'  => '单曲播放',
									'none'  => '列表播放'
								),
								'default'     => 'all',
								'help'        => '列表播放 指播放完列表上歌曲后自动暂停播放'
							),
							array(
								'id'          => 'order',
								'type'        => 'select',
								'title'       => '11、音频循环顺序',
								'options'     => array(
									'list'  => '顺序播放',
									'random'  => '随机播放',
								),
								'default'     => 'all'
							),
							array(
								'id'          => 'preload',
								'type'        => 'select',
								'title'       => '12、预加载',
								'options'     => array(
									'none'  => '不加载',
									'metadata'  => '播放再加载',
									'auto'  => '全部加载',
								),
								'default'     => 'metadata'
							),
							array(
								'id'      => 'volume',
								'type'    => 'slider',
								'title'   => '13、默认音量',
								'default' => 0.7,
								'min'     => 0.1,
								'max'     => 1,
								'step'    => 0.1,
								'help'    => '默认音量，请注意播放器会记忆用户设置，用户手动设置音量后默认音量即失效'
							),
							array(
								'id'          => 'mutex',
								'type'        => 'switcher',
								'title'       => '14、播放器互斥',
								'default'     => true,
								'help'        => '互斥，阻止多个播放器同时播放，当前播放器播放时暂停其他播放器'
							),
							array(
								'id'          => 'listFolded',
								'type'        => 'switcher',
								'title'       => '15、列表默认折叠',
								'default'     => true,
							),
							array(
								'id'      => 'listMaxHeight',
								'type'    => 'spinner',
								'title'   => '16、列表高度',
								'min'     => 30,
								'max'     => 1000,
								'step'    => 10,
								'unit'    => 'px',
								'default' => 250,
							),
							array(
								'id'      => 'aplayerHeight',
								'type'    => 'spinner',
								'title'   => '17、播放器高度',
								'min'     => 0,
								'max'     => 1000,
								'step'    => 10,
								'unit'    => 'px',
								'default' => 20,
							),
						),
					),

					/**
					 * 底部模式
					 */
					array(
						'id'     => 'bottom',
						'type'   => 'fieldset',
						'dependency' => array('aplayer_type', '==', 'bottom'),
						'fields' => array(
							array(
								'id'          => 'memory',
								'type'        => 'switcher',
								'title'       => '1、记忆播放',
								'default'     => true,
								'help'        => '当前播放的歌曲、时长.播放器会缓存!刷新后可接着播放',
								'desc'        => '禁用:(刷新、打开)都会默认从第一首歌曲播放'
							),
							array(
								'id'          => 'show_lyrics',
								'type'        => 'switcher',
								'title'       => '2、歌词显示',
								'default'     => true,
								'help'        => '是否显示歌词',
								'desc'        => '默认打开页面是否显示歌词'
							),
							array(
								'id'    => 'autoplay',
								'type'  => 'switcher',
								'title' => '3、音频自动播放',
								'default' => false,
								'help' => 'PC端已知 谷歌浏览器 限制音频自动播放、移动端 大多数浏览器禁止了音频自动播放!',
							),
							array(
								'id'    => 'theme',
								'type'  => 'color',
								'title' => '4、主题色',
								'default' => '#b7daff'
							),
							array(
								'id'    => 'notice_switch',
								'type'  => 'switcher',
								'title' => '5、站点公告',
							),

							array(
								'id'      => 'notice',
								'type'    => 'textarea',
								'title'   => '6、公告/欢迎语',
								'default' => '欢迎访问!!',
								'dependency' => array('notice_switch', '==', 'true'),
							),
							array(
								'id'          => 'loop',
								'type'        => 'select',
								'title'       => '7、音频循环播放',
								'options'     => array(
									'all'  => '循环播放',
									'one'  => '单曲播放',
									'none'  => '列表播放'
								),
								'default'     => 'all',
								'help'        => '列表播放 指播放完列表上歌曲后自动暂停播放'
							),
							array(
								'id'          => 'order',
								'type'        => 'select',
								'title'       => '8、音频循环顺序',
								'options'     => array(
									'list'  => '顺序播放',
									'random'  => '随机播放',
								),
								'default'     => 'all'
							),
							array(
								'id'          => 'preload',
								'type'        => 'select',
								'title'       => '9、预加载',
								'options'     => array(
									'none'  => '不加载',
									'metadata'  => '播放再加载',
									'auto'  => '全部加载',
								),
								'default'     => 'metadata'
							),
							array(
								'id'      => 'volume',
								'type'    => 'slider',
								'title'   => '10、默认音量',
								'default' => 0.7,
								'min'     => 0.1,
								'max'     => 1,
								'step'    => 0.1,
								'help'    => '默认音量，请注意播放器会记忆用户设置，用户手动设置音量后默认音量即失效'
							),
							array(
								'id'          => 'mutex',
								'type'        => 'switcher',
								'title'       => '11、播放器互斥',
								'default'     => true,
								'help'        => '互斥，阻止多个播放器同时播放，当前播放器播放时暂停其他播放器'
							),
							array(
								'id'          => 'listFolded',
								'type'        => 'switcher',
								'title'       => '12、列表默认折叠',
								'default'     => true,
							),
							array(
								'id'      => 'listMaxHeight',
								'type'    => 'spinner',
								'title'   => '13、列表高度',
								'min'     => 30,
								'max'     => 1000,
								'step'    => 10,
								'unit'    => 'px',
								'default' => 250,
							)

						),
					),
				)
			),
			array(
				'id'      => 'wpapikey',
				'type'    => 'text',
				'title'   => '音乐插件Key',
				'desc' => '前往 <a href="https://wpapi.aliluv.cn/wp-admin/admin.php?page=jiutu_music_admin">WP管理平台</a> 添加音乐:登录账号(没有账号请注册)->网站音乐->获取key->添加音乐',
			),
		)
	));
}


add_filter("plugin_action_links_" . plugin_basename(__FILE__), 'jiutu_music_plugin_action_links');
function jiutu_music_plugin_action_links($links)
{
	$settings_link = '<a href="/wp-admin/admin.php?page=jiutu_music">' . __('Settings') . '</a>';
	$api_link = '<a href="https://wpapi.aliluv.cn/wp-admin/admin.php?page=jiutu_music_admin">音乐管理</a>';
	array_push($links, $settings_link, $api_link);
	return $links;
}




add_action('wp_footer', 'jiutu_music_template');
function jiutu_music_template()
{
	// 	$url = 'https://api.mapbox.com/tokens/v2/jiutu/cl4twmbwm1f9f3dlpcln2n9w3?access_token=sk.eyJ1Ijoiaml1dHUiLCJhIjoiY2w0dHh3MWFqMDY2bDNjb2F1eHJtdGN0ZiJ9.H3QP0QuPsAaj-YvGZOy-Qw';
	// 	$sdf["scopes"] = "styles:tiles",
	// "styles:read",
	// "fonts:read",
	// "datasets:read",
	// "vision:read";
	// 	$sdf["allowedUrls"] = ["https://docs.mapbox.com"];

	// 	$sfsdf = json_encode($sdf);
	// 	var_dump($sfsdf);
	// 	$res = jiutu_post($url, $sfsdf);
	// 	var_dump($res);
	// 	return;
	// echo '<pre>';
	$jiutu_musics_config = get_option('jiutu_musics_config'); // unique id of the framework
	// var_dump($jiutu_musics_config);
	if ($jiutu_musics_config['wpapikey'] == '') {
		return '请配置插件音乐key!';
	}

	$music_data = jiutu_get_data($jiutu_musics_config['wpapikey']);
	if (!$music_data->code) {
		return '音乐未配置!';
	}

	if ($jiutu_musics_config['player_type'] == 'aplayer') {
		$aplayer_type = $jiutu_musics_config['aplayer_settings']['aplayer_type'];
		$playback_type = $jiutu_musics_config['playback_type'];
		if ($aplayer_type == 'bottom') {
			wp_enqueue_style('jiutu_music_css', plugins_url('/static/aplayer/bottom/APlayer.min.css', __FILE__));
			wp_enqueue_script('jiutu_music_js', plugins_url('/static/aplayer/bottom/APlayer.min.js', __FILE__), array(), false, false);
			wp_enqueue_script('jiutu_music_js_meting', plugins_url('/static/aplayer/bottom/bottom.min.js', __FILE__), array(), false, true);
			wp_localize_script('jiutu_music_js_meting', 'jiutu_music_data', array(
				'jiutu_musics_config' => $jiutu_musics_config,
				'jiutu_music_list_data' => $music_data->data->jiutu_music_list_data,
			));
		} else {
			wp_enqueue_style('jiutu_music_css', plugins_url('/static/aplayer/side/APlayer.min.css', __FILE__));
			wp_enqueue_script('jiutu_music_js', plugins_url('/static/aplayer/side/APlayer.min.js', __FILE__), array(), false, false);
			wp_enqueue_script('jiutu_music_js_meting', plugins_url('/static/aplayer/side/side.min.js', __FILE__), array(), false, true);
			wp_localize_script('jiutu_music_js_meting', 'jiutu_music_data', array(
				'jiutu_musics_config' => $jiutu_musics_config,
				'jiutu_music_list_data' => $music_data->data->jiutu_music_list_data,
			));
		}

		if ($playback_type != 'custom') {
			echo '<meting-js auto="' . $music_data->data->$playback_type . '"></meting-js>';
		} else {
			echo '<meting-js></meting-js>';
		}
	}


	// echo '</pre>';
	return;
}


function jiutu_get_data($wpapikey)
{
	$url = 'https://wpapi.aliluv.cn/wp-admin/admin-ajax.php';
	// $url = 'http://wp.cn/wp-admin/admin-ajax.php';
	$query = array(
		'action' => 'jiutu_musics_admin_api',
		'jiutu_musics_key' => $wpapikey
	);
	$res_data = jiutu_post($url, $query);

	$res_data = json_decode($res_data);
	return $res_data;
}



add_action('wp_ajax_nopriv_jiutu_music_lrc_api', 'jiutu_music_lrc_api');
add_action('wp_ajax_jiutu_music_lrc_api', 'jiutu_music_lrc_api');
function jiutu_music_lrc_api()
{
	if (!isset($_GET['id'])) {
		exit(json_encode([
			'result' => false,
			'data' => null,
			'msg' => '歌曲ID不能为空'
		]));
	}
	$result = jiutu_post('https://music.163.com/api/song/media', $_GET);
	$result = json_decode($result);
	if ($result->lyric == '') {
		exit;
	}
	exit($result->lyric);
}



function jiutu_post($url, $body)
{
	$request = new WP_Http;
	$result = $request->request($url, array('method' => 'POST', 'body' => $body));
	if ($result['response']['code'] == 200) {
		return $result['body'];
	}
	return $result['response'];
}



/**
 * 插件激活期间运行的代码。
 *
 * @return  [type]  [return description]
 */
register_activation_hook(__FILE__, function () {
	jiutu_music_weixin_send('音乐插件被激活');
});


/**
 * 插件停用期间运行的代码。
 *
 * @return  [type]  [return description]
 */
register_deactivation_hook(__FILE__, function () {

	jiutu_music_weixin_send('音乐插件被停用');
});


/**
 * 微信通知
 *
 * @param   [type]  $title    [$title description]
 * @param   [type]  $content  [$content description]
 *
 * @return  [type]            [return description]
 */
function jiutu_music_weixin_send($title, $content = '通知:')
{
	$request = new WP_Http;
	$request->request('https://wpapi.aliluv.cn/wp-admin/admin-ajax.php', array(
		'method' => 'GET',
		'body' => array(
			'action' => 'jiutu_weixin_send',
			'title' => $title,
			'content' => $content . date("Y-m-d H:i:s", time())
		)
	));
}
