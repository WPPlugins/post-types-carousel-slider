<?php
class postcs_CSS_JS extends postcsCls {

	public function __construct() {
		/*Js global var*/
		add_action ( 'wp_head', array( $this, 'postcs_js_var' ));

		/*Add Css JS*/
		add_action( 'wp_enqueue_scripts', array( $this, 'postcs_cssjs' ) );

		/*Add Css JS in wp-admin*/
		add_action( 'admin_enqueue_scripts', array( $this, 'postcs_cssjs' ) );

		/*Add style in wp-admin footer*/
		add_action('admin_footer', array( $this, 'post_cs_add_style_footer' ) );
	}

	/*JS global variable for ajax function*/
	public function postcs_js_var() {
		?>
		<script type="text/javascript">
			var ajaxurl = <?php echo json_encode( admin_url( 'admin-ajax.php' ) ); ?>;
		</script>
		<?php
	}

	/*Add Css, Js in front & admin*/
	public function postcs_cssjs() {
		/*https://daneden.github.io/animate.css*/
		wp_register_style( 'postcs-animate', plugins_url( '/assets/css/animate.css', dirname(__FILE__) ) );
		wp_enqueue_style( 'postcs-animate' );

		/*Custom CSS*/
		wp_register_style( 'postcs-css', plugins_url( 'assets/css/post-cs.css', dirname(__FILE__) ) );
		wp_enqueue_style( 'postcs-css' );

		/*Custom JS*/
		wp_register_script( 'postcs-js', plugins_url( 'assets/js/post-cs.js', dirname(__FILE__) ), '', '', true );
		wp_enqueue_script( 'postcs-js' );
	}

	/*Add style in wp-admin footer*/
	public function post_cs_add_style_footer() {
		?>
		<style>
			#psbox pre{white-space:nowrap;margin-top:0}
			#psbox #col-left{float:right}
			#psbox #col-right{float:left}
			#psbox .alain-right{float:right}
			#psbox .rateme{color:green;text-align:center}
			#psbox .content-box{background:white;padding:20px;margin:0 0 10px 0}
			#psbox .content-box h3{margin:0 0 10px 0}
			#psbox .psform code{margin-right:2px;margin-bottom:5px;display:inline-block}
			#psbox .design-option input{opacity:0;height:0;width:0;min-width:0;padding:0!important;float:left!important}
			#psbox .design-option label.checked{background:#0085ba;color:#fff}
		</style>
		<?php
	}

}

$postcs_CSS_JS = new postcs_CSS_JS();