;
(function(d)
{
	var k = d.scrollTo = function(a, i, e)
		{
			d(window).scrollTo(a, i, e)
		};
	k.defaults = {
		axis: 'xy',
		duration: parseFloat(d.fn.jquery) >= 1.3 ? 0 : 1
	};
	k.window = function(a)
	{
		return d(window)._scrollable()
	};
	d.fn._scrollable = function()
	{
		return this.map(function()
		{
			var a = this,
				i = !a.nodeName || d.inArray(a.nodeName.toLowerCase(), ['iframe', '#document', 'html', 'body']) != -1;
			if(!i) return a;
			var e = (a.contentWindow || a).document || a.ownerDocument || a;
			return d.browser.safari || e.compatMode == 'BackCompat' ? e.body : e.documentElement
		})
	};
	d.fn.scrollTo = function(n, j, b)
	{
		if(typeof j == 'object')
		{
			b = j;
			j = 0
		}
		if(typeof b == 'function') b = {
			onAfter: b
		};
		if(n == 'max') n = 9e9;
		b = d.extend(
		{}, k.defaults, b);
		j = j || b.speed || b.duration;
		b.queue = b.queue && b.axis.length > 1;
		if(b.queue) j /= 2;
		b.offset = p(b.offset);
		b.over = p(b.over);
		return this._scrollable().each(function()
		{
			var q = this,
				r = d(q),
				f = n,
				s, g = {},
				u = r.is('html,body');
			switch(typeof f)
			{
			case 'number':
			case 'string':
				if(/^([+-]=)?\d+(\.\d+)?(px|%)?$/.test(f))
				{
					f = p(f);
					break
				}
				f = d(f, this);
			case 'object':
				if(f.is || f.style) s = (f = d(f)).offset()
			}
			d.each(b.axis.split(''), function(a, i)
			{
				var e = i == 'x' ? 'Left' : 'Top',
					h = e.toLowerCase(),
					c = 'scroll' + e,
					l = q[c],
					m = k.max(q, i);
				if(s)
				{
					g[c] = s[h] + (u ? 0 : l - r.offset()[h]);
					if(b.margin)
					{
						g[c] -= parseInt(f.css('margin' + e)) || 0;
						g[c] -= parseInt(f.css('border' + e + 'Width')) || 0
					}
					g[c] += b.offset[h] || 0;
					if(b.over[h]) g[c] += f[i == 'x' ? 'width' : 'height']() * b.over[h]
				}
				else
				{
					var o = f[h];
					g[c] = o.slice && o.slice(-1) == '%' ? parseFloat(o) / 100 * m : o
				}
				if(/^\d+$/.test(g[c])) g[c] = g[c] <= 0 ? 0 : Math.min(g[c], m);
				if(!a && b.queue)
				{
					if(l != g[c]) t(b.onAfterFirst);
					delete g[c]
				}
			});
			t(b.onAfter);

			function t(a)
			{
				r.animate(g, j, b.easing, a &&
				function()
				{
					a.call(this, n, b)
				})
			}
		}).end()
	};
	k.max = function(a, i)
	{
		var e = i == 'x' ? 'Width' : 'Height',
			h = 'scroll' + e;
		if(!d(a).is('html,body')) return a[h] - d(a)[e.toLowerCase()]();
		var c = 'client' + e,
			l = a.ownerDocument.documentElement,
			m = a.ownerDocument.body;
		return Math.max(l[h], m[h]) - Math.min(l[c], m[c])
	};

	function p(a)
	{
		return typeof a == 'object' ? a : {
			top: a,
			left: a
		}
	}
})(jQuery);
(function($)
{
	var l = location.href.replace(/#.*/, '');
	var g = $.localScroll = function(a)
		{
			$('body').localScroll(a)
		};
	g.defaults = {
		duration: 1e3,
		axis: 'y',
		event: 'click',
		stop: true,
		target: window,
		reset: true
	};
	g.hash = function(a)
	{
		if(location.hash)
		{
			a = $.extend(
			{}, g.defaults, a);
			a.hash = false;
			if(a.reset)
			{
				var e = a.duration;
				delete a.duration;
				$(a.target).scrollTo(0, a);
				a.duration = e
			}
			i(0, location, a)
		}
	};
	$.fn.localScroll = function(b)
	{
		b = $.extend(
		{}, g.defaults, b);
		return b.lazy ? this.bind(b.event, function(a)
		{
			var e = $([a.target, a.target.parentNode]).filter(d)[0];
			if(e) i(a, e, b)
		}) : this.find('a,area').filter(d).bind(b.event, function(a)
		{
			i(a, this, b)
		}).end().end();

		function d()
		{
			return !!this.href && !! this.hash && this.href.replace(this.hash, '') == l && (!b.filter || $(this).is(b.filter))
		}
	};

	function i(a, e, b)
	{
		var d = e.hash.slice(1),
			f = document.getElementById(d) || document.getElementsByName(d)[0];
		if(!f) return;
		if(a) a.preventDefault();
		var h = $(b.target);
		if(b.lock && h.is(':animated') || b.onBefore && b.onBefore.call(b, a, f, h) === false) return;
		if(b.stop) h.stop(true);
		if(b.hash)
		{
			var j = f.id == d ? 'id' : 'name',
				k = $('<a> </a>').attr(j, d).css(
				{
					position: 'absolute',
					top: $(window).scrollTop(),
					left: $(window).scrollLeft()
				});
			f[j] = '';
			$('body').prepend(k);
			location = e.hash;
			k.remove();
			f[j] = d
		}
		h.scrollTo(f, b).trigger('notify.serialScroll', [f])
	}
})(jQuery);
jQuery(function($)
{
	$.localScroll.defaults.axis = 'xy';
	$.localScroll.hash(
	{
		queue: true,
		duration: 3000
	});
	$.localScroll(
	{
		queue: true,
		duration: 1000,
		hash: true,
		onBefore: function(e, anchor, $target)
		{},
		onAfter: function(anchor, settings)
		{}
	})
});
$(document).ready(function()
{
	$('.hide_bomb').fadeOut(6000);
	$("#content, #footer, #social-networks").stop().animate(
	{
		"opacity": "1"
	}, 2000);
	$(".fade_jquery").hover(function()
	{
		$(this).stop().animate(
		{
			"opacity": "1"
		}, "slow")
	}, function()
	{
		$(this).stop().animate(
		{
			"opacity": "0.5"
		}, "slow")
	});
	$('.error,.success').css('margin-left', '-1000px');
	$('.error,.success').animate(
	{
		marginLeft: '+=1000px',
	}, 2000, function()
	{})
});
$(".hide_this").click(function()
{
	$(this).slideUp(2000)
});
$(function()
{
	$('.slidedown').slideDown(1500)
});

function hide_intro()
{
	$('#intro').slideUp(2000);
	$.ajax(
	{
		type: 'POST',
		url: '/ajax/hide_intro.php'
	});
	return false
};

$(document).ready(function()
{
	$("#submit_fact_admin").submit(function()
	{
		$('#notification').html('');
		$('#notification').html('<div class="error">Loading..</div>').slideDown();
		var textarea_text_fact = $('#textarea_text_fact').val();

		$.post("/ajax/add_fact.php", {
			text_fact: textarea_text_fact,
			admin: true
		}, function(data)
		{
			if(data == 1)
			{
				var div_response = '<div class="success">Your Fact has been added successfully!</div>';
			}
			else
			{
				var div_response = '<div class="error">Your Fact could not be added.</div>';
			}

			$('#notification').html(div_response).delay(3000).slideUp();
			$('#textarea_text_fact').val('');
			$('#textarea_text_fact').focus();
		});
		return false;
	});
});
$(document).ready(function()
{
	$("#form_newsletter").submit(function()
	{
		$('#notification').html('');
		$('#notification').html('<div class="error">Loading..</div>').slideDown();
		var input_email = $('#input_email').val();

		if(validateEmail(input_email) == false || input_email.length === 0)
		{
			$('#notification').html('<div class="error">Please enter a valid email address.</div>').delay(3000).slideUp();
		}
		else
		{
			$.post("/ajax/newsletter.php", {
				email: input_email
			}, function(data)
			{
				if(data == 1)
				{
					var div_response = '<div class="success">You are now subscribed to the newsletter!</div>';
				}
				else if(data == 0)
				{
					var div_response = '<div class="error">Please enter a valid email address.</div>';
				}
				else if(data == 2)
				{
					var div_response = '<div class="error">Oops, you are already subscribed to our newsletter with this email address (' + input_email + ').</div>';
				}

				$('#notification').html(div_response).delay(3000).slideUp();
			});
		}
		$('#input_email').val('').focus();
		return false;
	});
});

function validateEmail(email)
{
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	if(!emailReg.test(email))
	{
		return false;
	}
	else
	{
		return true;
	}
}

function validateUsername(username)
{
	var usernameReg = /^([\w]{4,})?$/;
	if(!usernameReg.test(username))
	{
		return false;
	}
	else
	{
		return true;
	}
}

function edit_fact(id_fact)
{
	var txt_fact = $(".txt_fact[data-id=" + id_fact + "]").text();
	$(".txt_fact[data-id=" + id_fact + "]").html('' + '<form action="/ajax/edit_fact.php" method="post" id="edit_fact">' + '<textarea name="text_fact" id="text_fact">' + txt_fact + '</textarea>' + '<input type="hidden" name="id_fact" value="' + id_fact + '"/>' + '<br /><br />' + '<input type="submit" value="Edit this fact"/><br/>' + '<div class="clear"></div>' + '</form>');

	$('form#edit_fact textarea#text_fact').focus();
}

// Send a form using Ajax when the form has been generated by Ajax
$("#edit_fact").live("submit", function(event)
{
	event.preventDefault();
	var form = $(this);

	$.ajax(
	{
		url: form.attr('action'),
		// Get the action URL to send AJAX to
		type: "POST",
		data: form.serialize(),
		// get all form variables
		success: function(result)
		{
			if(result >= 1) // return the ID of the Fact
			{
				moderate_fact('yes', result); // approve the fact
			}
			else
			{
				alert('An error occured.');
			}
		}
	});
});

function moderate_fact(approve, id_fact)
{
	$(".footer_fact[data-id=" + id_fact + "]").html("<em>Wait...</em>");
	$.ajax(
	{
		type: 'post',
		url: 'http://internet-facts.com/ajax/moderate_facts.php',
		data: {
			id_fact: id_fact,
			approve: approve
		},
		success: function(data)
		{
			var nb_facts_moderation = $("#nb_facts_moderation").html();
			var txt_fact = $("#txt_fact").html();

			if(nb_facts_moderation - 1 <= 1 && txt_fact == 'Facts')
			{
				$("#txt_fact").html('Fact');
			}
			$("#nb_facts_moderation").html(nb_facts_moderation - 1);

			$(".footer_fact[data-id=" + id_fact + "]").html(data);
			$(".post[data-id=" + id_fact + "]").delay(1000).slideUp(500);
		}
	});

	return false;
}


$(document).ready(function()
{
	$("#submit_fact_user").submit(function()
	{
		$('#notification').html('');
		$('#notification').html('<div class="error">Loading..</div>').slideDown();
		var username_form = $('#username').val().toLowerCase();
		var email_form = $('#email').val();
		var text_fact_form = $('#textarea_text_fact').val();

		if(validateUsername(username_form) == false || username_form.length === 0)
		{
			$('#notification').html('<div class="error">Please enter a valid username.</div>').delay(3000).slideUp();
			$('#username').val('').focus();
		}
		else
		{
			if(validateEmail(email_form) == false || email_form.length === 0)
			{
				$('#notification').html('<div class="error">Please enter a valid email address.</div>').delay(3000).slideUp();
				$('#email').val('').focus();
			}
			else
			{
				if(text_fact_form.length < 50)
				{
					var target = 50 - text_fact_form.length;
					var characters = 'characters';

					if(target == 1)
					{
						characters = 'character';
					}
					$('#notification').html('<div class="error">Your Fact is too short! It should be at least 50 characters (' + target + ' ' + characters + ' left).</div>').delay(3000).slideUp();
					$('#textarea_text_fact').focus();
				}
				else
				{
					$.post("/ajax/add_fact.php", {
						text_fact: text_fact_form,
						username: username_form,
						email: email_form,
						is_visitor: true
					}, function(data)
					{
						if(data == 1)
						{
							var div_response = '<div class="success">Your Fact has been added successfully!</div>';
							$('#textarea_text_fact').val('').focus();
						}
						else if(data == 0)
						{
							var div_response = '<div class="error">The form is not filled correctly.</div>';
						}
						else if(data == 2)
						{
							var div_response = '<div class="error">This email is not associated with the current username.</div>';
							$('#email').val('').focus();
						}
						else if(data == 3)
						{
							var div_response = '<div class="error">You\'ve added too much Facts for today! Come back tomorrow.</div>';
						}

						$('#notification').html(div_response).delay(3000).slideUp();
					});
				}

			}
		}
		return false;
	});

});