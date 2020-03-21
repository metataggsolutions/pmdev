<?php
/**
 * Fires after the main content, before the footer is output.
 *
 * @since 3.10
 */
do_action( 'et_after_main_content' );

if ( 'on' === et_get_option( 'divi_back_to_top', 'false' ) ) : ?>

	<span class="et_pb_scroll_top et-pb-icon"></span>

<?php endif;

if ( ! is_page_template( 'page-template-blank.php' ) ) : ?>
	
	<?php global $post;
	if(!is_singular('post') && !is_page(32847) /*&& !is_page(32847)*/ ) {?>	
	<section class="footer_top pmlocations">
    	<div class="ft_container">
			<p>
				Effective Tuesday, March 17 & until further notice, our store hours will be as follows: <a href="#temporary-store-hours">Temporary Store Hours</a>
			</p>&nbsp;
        	<div class="ftrow et_had_animation">
            	<?php dynamic_sidebar('footer-top'); ?>
            </div> 
        </div>
        <div class="ft_container lmiddle">
			            <h4>FOOD SERVICE Distribution Warehouses</h4>
            <div class="ftrow et_had_animation">
                <?php dynamic_sidebar('footer-middle'); ?>
            </div>
        </div>
	</section>
	<?php } ?>



<?php if(is_woocommerce() || is_cart() || is_checkout()) { ?>
<style id="et-builder-module-design-34442-cached-inline-styles">.et_pb_section_0{border-top-width:50px;border-top-color:#d22630}.et_pb_section_1{border-top-width:50px;border-top-color:#d22630}.et_pb_section_0.et_pb_section{padding-top:35px;padding-bottom:30px}.et_pb_section_1.et_pb_section{padding-top:35px;padding-bottom:30px}.et_pb_row_0.et_pb_row{padding-top:0px!important;padding-top:0px}.et_pb_text_0 h2{font-size:20px}.et_pb_text_0 h3{font-family:'Oswald',Helvetica,Arial,Lucida,sans-serif;text-transform:uppercase;font-size:27px}@media only screen and (min-width:981px){.et_pb_section_0{width:49%}.et_pb_section_1{width:49%}}@media only screen and (max-width:980px){.et_pb_section_0{border-top-width:50px;border-top-color:#d22630;width:52%}.et_pb_section_1{border-top-width:50px;border-top-color:#d22630;width:52%}}@media only screen and (max-width:767px){.et_pb_section_0{border-top-width:50px;border-top-color:#d22630}.et_pb_section_1{border-top-width:50px;border-top-color:#d22630}.et_pb_text_0 h3{font-size:23px}}</style><!-- WooCommerce JavaScript -->
<?php echo do_shortcode('[et_pb_section global_module="36987"][/et_pb_section]'); ?>
<?php } ?>
			<footer id="main-footer">
				<?php get_sidebar( 'footer' ); ?>
		<?php
			if ( has_nav_menu( 'footer-menu' ) ) : ?>

				<div id="et-footer-nav">
					<div class="container">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'footer-menu',
								'depth'          => '1',
								'menu_class'     => 'bottom-nav',
								'container'      => '',
								'fallback_cb'    => '',
							) );
						?>
					</div>
				</div> <!-- #et-footer-nav -->

			<?php endif; ?>
                
                <div class="footer_middle">
                    <div class="container clearfix">
                    <div class="footer_email">
                        <?php dynamic_sidebar('sidebar-7'); ?>
                    </div>
                    <?php 
                    if ( false !== et_get_option( 'show_footer_social_icons', true ) ) {
						get_template_part( 'includes/social_icons', 'footer' );
					}
                    ?>
                    </div>
                </div>

				<div id="footer-bottom">
					<div class="container clearfix">
				<?php 
                        
					// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
					echo et_core_fix_unclosed_html_tags( et_core_esc_previously( et_get_footer_credits() ) );
					// phpcs:enable
				?>
					</div>	<!-- .container -->
				</div>
			</footer> <!-- #main-footer -->
		</div> <!-- #et-main-area -->

<?php endif; ?>

	</div> <!-- #page-container -->
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<?php wp_footer(); ?>   

	<?php if(!is_front_page()) { ?> 
	<script>				
	(function ($) {
	'use strict';

	const DataStatePropertyName = 'multiselect';
	const EventNamespace = '.multiselect';
	const PluginName = 'MultiSelect';

	var old = $.fn[PluginName];
	$.fn[PluginName] = plugin;
	$.fn[PluginName].Constructor = MultiSelect;
	$.fn[PluginName].noConflict = function () {
		$.fn[PluginName] = old;
		return this;
	};
	// Defaults
	$.fn[PluginName].defaults = {
	};
	// Static members
	$.fn[PluginName].EventNamespace = function () {
		return EventNamespace.replace(/^\./ig, '');
	};
	$.fn[PluginName].GetNamespacedEvents = function (eventsArray) {
		return getNamespacedEvents(eventsArray);
	};
	function getNamespacedEvents(eventsArray) {
		var event;
		var namespacedEvents = "";
		while (event = eventsArray.shift()) {
			namespacedEvents += event + EventNamespace + " ";
		}
		return namespacedEvents.replace(/\s+$/g, '');
	}
	function plugin(option) {
		this.each(function () {
			var $target = $(this);
			var multiSelect = $target.data(DataStatePropertyName);
			var options = (typeof option === typeof {} && option) || {};

			if (!multiSelect) {
				$target.data(DataStatePropertyName, multiSelect = new MultiSelect(this, options));
			}

			if (typeof option === typeof "") {
				if (!(option in multiSelect)) {
					throw "MultiSelect does not contain a method named '" + option + "'";
				}
				return multiSelect[option]();
			}
		});
	}
	function MultiSelect(element, options) {
		this.$element = $(element);
		this.options = $.extend({}, $.fn[PluginName].defaults, options);
		this.destroyFns = [];

		this.$toggle = this.$element.children('.toggle');
		this.$toggle.attr('id', this.$element.attr('id') + 'multi-select-label');
		this.$backdrop = null;
		this.$allToggle = null;

		init.apply(this);
	}
	MultiSelect.prototype.open = open;
	MultiSelect.prototype.close = close;

	function init() {
		this.$element
			.addClass('multi-select')
			.attr('tabindex', 0);

		initAria.apply(this);
		initEvents.apply(this);
		updateLabel.apply(this);
		// injectToggleAll.apply(this);

		this.destroyFns.push(function () {
			return '|'
		});
	}
	function injectToggleAll() {
		if (this.$allToggle && !this.$allToggle.parent()) {
			this.$allToggle = null;
		}
		this.$allToggle = $("<li><label><input type='checkbox'/>(all)</label><li>");
		this.$element
			.children('ul:first')
			.prepend(this.$allToggle);
	}
	function initAria() {
		this.$element
			.attr('role', 'combobox')
			.attr('aria-multiselect', true)
			.attr('aria-expanded', false)
			.attr('aria-haspopup', false)
			.attr('aria-labeledby', this.$element.attr("aria-labeledby") + " " + this.$toggle.attr('id'));

		this.$toggle
			.attr('aria-label', '');
	}
	function initEvents() {
		var that = this;
		this.$element
			.on(getNamespacedEvents(['click']), function ($event) {
				if ($event.target !== that.$toggle[0] && !that.$toggle.has($event.target).length) {
					return;
				}

				if ($(this).hasClass('in')) {
					that.close();
				} else {
					that.open();
				}
			})
			.on(getNamespacedEvents(['keydown']), function ($event) {
				var next = false;
				switch ($event.keyCode) {
					case 13:
						if ($(this).hasClass('in')) {
							that.close();
						} else {
							that.open();
						}
						break;
					case 9:
						if ($event.target !== that.$element[0]) {
							$event.preventDefault();
						}
					case 27:
						that.close();
						break;
					case 40:
						next = true;
					case 38:
						var $items = $(this)
							.children("ul:first")
							.find(":input, button, a");

						var foundAt = $.inArray(document.activeElement, $items);
						if (next && ++foundAt === $items.length) {
							foundAt = 0;
						} else if (!next && --foundAt < 0) {
							foundAt = $items.length - 1;
						}

						$($items[foundAt])
							.trigger('focus');
				}
			})
			.on(getNamespacedEvents(['focus']), 'a, button, :input', function () {
				$(this)
					.parents('li:last')
					.addClass('focused');
			})
			.on(getNamespacedEvents(['blur']), 'a, button, :input', function () {
				$(this)
					.parents('li:last')
					.removeClass('focused');
			})
			.on(getNamespacedEvents(['change']), ':checkbox', function () {
				if (that.$allToggle && $(this).is(that.$allToggle.find(':checkbox'))) {
					var allChecked = that.$allToggle
						.find(':checkbox')
						.prop("checked");

					that.$element
						.find(':checkbox')
						.not(that.$allToggle.find(":checkbox"))
						.each(function () {
							$(this).prop("checked", allChecked);
							$(this)
								.parents('li:last')
								.toggleClass('selected', $(this).prop('checked'));
						});

					updateLabel.apply(that);
					return;
				}

				$(this)
					.parents('li:last')
					.toggleClass('selected', $(this).prop('checked'));

				var checkboxes = that.$element
					.find(":checkbox")
					.not(that.$allToggle.find(":checkbox"))
					.filter(":checked");

				that.$allToggle.find(":checkbox").prop("checked", checkboxes.length === checkboxes.end().length);

				updateLabel.apply(that);
			})
			.on(getNamespacedEvents(['mouseover']), 'ul', function () {
				$(this)
					.children(".focused")
					.removeClass("focused");
			});
	}
	function updateLabel() {
		var pluralize = function (wordSingular, count) {
			if (count !== 1) {
				switch (true) {
					case /y$/.test(wordSingular):
						wordSingular = wordSingular.replace(/y$/, "ies");
					default:
						wordSingular = wordSingular + "s";
				}
			}
			return wordSingular;
		} 
		var $checkboxes = this.$element
			.find('ul :checkbox');
		var allCount = $checkboxes.length;
		var checkedCount = $checkboxes.filter(":checked").length;
		var label = checkedCount + " " + pluralize("item", checkedCount) + " selected";

		this.$toggle
			.children("label")
			.text('Select Tag');

		this.$element
			.children('ul')
			.attr("aria-label", label + " of " + allCount + " " + pluralize("item", allCount));
	}
	function ensureFocus() {
		this.$element
			.children("ul:first")
			.find(":input, button, a")
			.first()
			.trigger('focus')
			.end()
			.end()
			.find(":checked")
			.first()
			.trigger('focus');
	}
	function addBackdrop() {
		if (this.$backdrop) {
			return;
		}

		var that = this;
		this.$backdrop = $("<div class='multi-select-backdrop'/>");
		this.$element.append(this.$backdrop);

		this.$backdrop
			.on('click', function () {
				$(this)
					.off('click')
					.remove();

				that.$backdrop = null;
				that.close();
			});
	}
	function open() {
		if (this.$element.hasClass('in')) {
			return;
		}

		this.$element
			.addClass('in');

		this.$element
			.attr('aria-expanded', true)
			.attr('aria-haspopup', true);

		addBackdrop.apply(this);
		//ensureFocus.apply(this);
	}
	function close() {
		this.$element
			.removeClass('in')
			.trigger('focus');

		this.$element
			.attr('aria-expanded', false)
			.attr('aria-haspopup', false);

		if (this.$backdrop) {
			this.$backdrop.trigger('click');
		}
	}
})(jQuery);
$(document).ready(function () {
	$('#multi-select-plugin')
		.MultiSelect();
});

$(document).ready(function() {
    $("input:reset").click(function() {      
        this.form.reset();
  		$("form input[type='checkbox']:checked").prop("checked", false);

         return false;                        
    });
});
$(document).ready(function() {
$( "#multi-select-plugin ul").mouseleave(function() {
//	alert("tewst"); 
	$('.multi-select-backdrop').trigger('click'); 
	
});
});
</script>
<?php } ?>
<?php if(!is_front_page()) { ?> 
<script>
$(document).ready(function() {
	$("#onsale").change(function() {
    if(this.checked) {
		window.location.href = "<?php echo get_site_url(); ?>/shop/?onsale=true";
	}
	else
	{
		window.location.href = "<?php echo get_site_url(); ?>/shop/?onsale=false";
	}
});

$("#sorting_name_sort").change(function() {
	if($(this).val()=='sortingasc') {
	//	window.location.href = "<?php echo get_site_url(); ?>/shop/?sort=asc";
		$("form[name='shopfilter']").submit();
	}
	if($(this).val()=='sortingdesc')
	{
	//	window.location.href = "<?php echo get_site_url(); ?>/shop/?sort=desc";
		$("form[name='shopfilter']").submit();
	}
	if($(this).val()=='sortingdefaultasc')
	{
	//	window.location.href = "<?php echo get_site_url(); ?>/shop/?sort=desc";
		$("form[name='shopfilter']").submit();
	}
	if($(this).val()=='sortingdefaultdesc')
	{
	//	window.location.href = "<?php echo get_site_url(); ?>/shop/?sort=desc";
		$("form[name='shopfilter']").submit();
	}
});
});
</script>
<?php } ?>
</body>
</html>