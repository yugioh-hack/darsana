<?php
// https://dainashiyesterday.com/post-2644/
// カテゴリーの選択を1つに制限
add_action( 'admin_print_footer_scripts', 'limit_category_select' );
function limit_category_select() {
?>
<script type="text/javascript">
jQuery(function($) {
// 投稿画面のカテゴリー選択を制限
var categorydiv = $( '#categorydiv input[type=checkbox]' );
categorydiv.click( function() {
$(this).parents( '#categorydiv' ).find( 'input[type=checkbox]' ).attr('checked', false);
$(this).attr( 'checked', true );
});
// クイック編集のカテゴリー選択を制限
var inline_edit_col_center = $( '.inline-edit-col-center input[type=checkbox]' );
inline_edit_col_center.click( function() {
$(this).parents( '.inline-edit-col-center' ).find( 'input[type=checkbox]' ).attr( 'checked', false );
$(this).attr( 'checked', true );
});
$( '#categorydiv #category-pop > ul > li:first-child, #categorydiv #category-all > ul > li:first-child, .inline-edit-col-center > ul.category-checklist > li:first-child' ).before( '<p style="padding-top:5px;">カテゴリーは1つしか選択できません</p>' );
});
</script>
<?php
}
