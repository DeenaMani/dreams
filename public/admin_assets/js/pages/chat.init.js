!function(e){function s(){if(message=e(".message-input .form-control").val(),""==e.trim(message))return!1;var a=new Date,s=a.getHours()+":"+a.getMinutes(),t='<li class="sent"><div class="conversation"><div class="dropdown"><a href="javascript:void(0)" data-toggle="dropdown" class="dropdown-toggle"><i class="bx bx-dots-vertical-rounded fs-sm"></i></a><div class="dropdown-menu"><a href="javascript:void(0)" class="dropdown-item">Attachments</a><a href="javascript:void(0)" class="dropdown-item">Status</a><a href="javascript:void(0)" class="dropdown-item">Forward</a><a href="javascript:void(0)" class="dropdown-item">Copy</a></div></div><div class="profile-img"><img src="assets/images/users/avatar-1.jpg" alt="Header Avatar" class="online avatar avatar-xs mr-0"></div><div class="text-msg"><p>'+message+'</p><span class="time-stamp">'+s+"</span></div></div>";e('<li class="sent">'+t+"</li>").appendTo(e(".user-messages ul")),e(".chat-input .form-control").val(null),e(".chat-list .active .media-body p").html('<span class="text-dark">You: </span>'+message)}["usermsg-scrollbar","chat","group","contact"].forEach(function(a){Scrollbar.init(document.getElementById(a))}),e("#profile .dropdown-item").click(function(){var a=e(this).attr("data-status");e(this).parents(".dropdown").find(".avatar").removeClass().addClass("avatar avatar-sm mr-0 "+a)}),e(".chat-send-btn").click(function(){s(),e(".message-input .form-control").val(null)}),e(window).on("keydown",function(a){if(13==a.which)return s(),e(".message-input .form-control").val(null),!1})}(jQuery);