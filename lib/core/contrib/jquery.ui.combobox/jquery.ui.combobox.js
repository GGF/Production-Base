(function($) {
		$.widget("ui.combobox", {
			_create: function() {
				var self = this;
				var select = this.element.hide();
				var input = $("<input>")
					.insertAfter(select)
					.val(select.children("option[selected]").text())
					.autocomplete({
						source: function(request, response) {
							var matcher = new RegExp(request.term, "i");
							response(select.contents().andSelf().children("option").map(function() {
								var text = $(this).text();
								if (!request.term || matcher.test(text))
									return {
										id: $(this).val(),
										label: text.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + request.term.replace(/([\^\$\(\)\[\]\{\}\*\.\+\?\|\\])/gi, "\\$1") + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>"),
										value: text
									};
							}));
						},
						delay: 0,
						select: function(e, ui) {
							if (!ui.item) {
								// remove invalid value, as it didn't match anything
								$(this).val("");
								return false;
							}
							$(this).focus();
							select.val(ui.item.id);
							self._trigger("selected", null, {
								item: select.find("[value='" + ui.item.id + "']")
							});
							select.trigger('onchange');
							
						},
						minLength: 0
					})
					.keyboard('enter',function(){$('[fieldid='+select.attr('fieldnext')+']').focus();})
					.addClass("ui-widget ui-widget-content ui-corner-left");
				select.focus(function(){
						input.val(select.children("option[selected]").text())
							.autocomplete("search", input.val())
							.focus();
						return false;
						});
				$("<button>&nbsp;</button>")
				//.attr('tabindex','-1')
				.insertAfter(input)
				.attr('tabindex','32000')
				.button({
					icons: {
						primary: "ui-icon-triangle-1-s"
					},
					text: false
				})
				.removeClass("ui-corner-all")
				.addClass("ui-corner-right ui-button-icon")
				/*.position({
					my: "left center",
					at: "right center",
					of: input,
					offset: "-1 0"
				})*/
				.css('top','')
				.hover(function(){$(this).toggleClass('cbMouseOver');})
				.click(function() {
					// close if already visible
					if ($(this).hasClass('cbMouseOver')) {
						if (input.autocomplete("widget").is(":visible")) {
							input.autocomplete("close");
							return false;
						}
						// pass empty string as value to search for, displaying all results
						input.autocomplete("search", "");
						input.focus();
					}
					return false;;
				});
			}
		});

	})(jQuery);