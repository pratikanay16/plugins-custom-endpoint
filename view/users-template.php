<?php
/**
 * User detail modal, include inside user list
 */

get_header();

?>
<main id="site-content" role="main">
	<div class="head_table">Users</div>
	<div class="customurl_table">
		<table id="userlist" class="display">
			<thead>
				<tr>
					<th>Id</th>
					<th>Name</th>
					<th>Username</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Zipcode</th>
					<th>Company</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Id</th>
					<th>Name</th>
					<th>Username</th>
					<th>Email</th>
					<th>Phone</th>
					<th>Zipcode</th>
					<th>Company</th>
				</tr>
			</tfoot>
		</table>
	</div>
</main><!-- #site-content -->
<?php
// Template for user detail view in popup.
require_once PLUGIN_DIR . 'view/user-detail.php';
// footer widget default from WordPress theme.
get_template_part( 'template-parts/footer-menus-widgets' );
// Footer.
get_footer();
