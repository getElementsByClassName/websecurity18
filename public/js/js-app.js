(function($){
  $(function(){

//load materiallizecss functions
$('.sidenav').sidenav();
$('.parallax').parallax();

let state = {};
let domainPath = 'http://localhost/websectest/';

//set initial state
fnSetState(window.location.href);

function fnSetState(url) {
  console.log(url.split('/'));
  //set state
  if (url.split('/')[4] === '') {
    state.url = 'pages/home';
  } else {
    if(url.split('/')[6] !== '') state.url = url.split('/')[4] + '/' + url.split('/')[5] + '/' + url.split('/')[6];
    else state.url = url.split('/')[4] + '/' + url.split('/')[5];
  }
  window.history.pushState(state, '', url);
  console.log(state.url);
}

//on browser back navigation
window.onpopstate = function (e) {
  e.preventDefault();
  console.log(e.state.url);
  fnNavigate(e.state.url);
}



//ajax navigation

$(document).on('click', '.link', function(e){
  e.preventDefault();

  let sPageToLoad = $(this).attr('data-go-to');

  fnNavigate(sPageToLoad);
});


function fnNavigate(sPageToLoad){

  let url = domainPath + sPageToLoad;
  console.log(url);

  $.get(url, function(data, status){
    if(status === 'success'){
      $('main').html(data);
      $('.sidenav').sidenav();
      $('.parallax').parallax();

      fnSetState(url);

    }else{
      alertify.error('Something went wrong with your request, try again later');
    }

  });
}



//Login form
$(document).on('submit', '#frm-login', function(e){
    event.preventDefault(); // Prevent the form from submitting via the browser

    var form = $(this);

    console.log(form.serialize());
    $.ajax({
      type: 'POST',
      url: `${domainPath}users/login`,
      data: form.serialize(),
      dataType: 'json',
    }).done(function (data) {
      if(data.status === '401'){
        console.log(data);
        //form.find('#token').val(data.token);
        if(data.hasOwnProperty('inputs')){
          for(let i = 0, j = data.inputs.length; i < j; i++){
            $('#'+ data.inputs[i] +'').removeClass('valid').addClass('invalid').
            siblings('.helper-text').text($('label[for='+ data.inputs[i] +']').attr('data-error'));
          }
        }else{
          alertify.error(data.message);
        }
      }
      if(data.status === '200'){
        alertify.success(data.message);
        fnNavigate('users/profile');
        console.log(data);
      }
    });

  });



//Register form
$(document).on('submit', '#frm-register', function(e){
    event.preventDefault(); // Prevent the form from submitting via the browser

    let form = $(this);

    console.log(form.serialize());
    $.ajax({
      type: 'POST',
      url: `${domainPath}users/register`,
      data: form.serialize(),
      dataType: 'json',
    }).done(function (data) {
      console.log(data);
      if(data.status === "200"){
        alertify.success(data.message);
        fnNavigate('users/login');
      }

      else if(data.status === '401'){
        if(data.hasOwnProperty('inputs')){
          for(let i = 0, j = data.inputs.length; i < j; i++){
            $('#'+ data.inputs[i] +'').removeClass('valid').addClass('invalid').
            siblings('.helper-text').text($('label[for='+ data.inputs[i] +']').attr('data-error'));
          }
        }
      }//end else if 401
      alertify.error(data.message);
    });
  });

//Upload Form
$(document).on('submit', '#frm-upload', function(e){
    e.preventDefault(); // Prevent the form from submitting via the browser

    $.ajax({
      type: 'POST',
      url: `${domainPath}images/upload`,
      data: new FormData(this),
      cache: false,
      contentType: false,
      processData: false,
      dataType: 'json'
    })
    .done(function(data){
      alertify.success(data.message);
      fnNavigate('users/profile');
    })

    .fail(function(data){
      alertify.error(data.message);
    });
  });

//on comments POST

$(document).on('submit', '#frm-comment', function(e){
  e.preventDefault();

  let form = $(this);

  let photoId = $(this).data('photoid');

  console.log(form.serialize());

  $.ajax({
    type: 'POST',
    url: `${domainPath}images/comment/${photoId}`,
    data: form.serialize(),
    dataType: 'json'

  })

  .done(function(data) {
    console.log(data.inputs);
    console.log(data.message);
    alertify.success(data.message);
    fnNavigate(`users/photo/${photoId}`);
  })

  .fail(function(data){
    alertify.error("Your comment could not be posted, try again later");
  })

})



//on album click, fetch albums
$(document).on('click', '.album, .shared-album', function(e){
  e.preventDefault(); // Prevent the form from submitting via the browser

  if ($(this).hasClass('album')) {
    var albumId = $(this).siblings('#album_id').text();
    var url = `${domainPath}users/album/${albumId}`;
    console.log(albumId);

  }else if($(this).hasClass('shared-album')){
    var albumId = $(this).siblings('#shared_album_id').text();
    var url = `${domainPath}users/sharedalbum/${albumId}`;
    console.log(albumId);

  }

  $.get(url, function(data, status){
    console.log(status);
    fnSetState(url);
    if(status === 'success' && data){
      $('main').html(data);

    }else{
      alertify.error('Something went wrong with your request, try again later');
    }
  });
});


//on click card-image, fetching single photo
$(document).on('click', '.photo', function(e){
  e.preventDefault();

  var photoId = $(this).parent('.card').data('photoid');
  var url = `${domainPath}users/photo/${photoId}`;
  console.log(photoId);

  $.get(url, function(data, status){
    fnSetState(url);
    if(status === 'success' && data){
      $('main').html(data);
    }else{
      alertify.error('Something went wrong');
    }
  })
});



//on share an album
$(document).on('click', '.share-album', function(e){
  e.preventDefault(); // Prevent the form from submitting via the browser

  let albumId = $(this).parent().parent().find("div:first-child").text();
  let albumName = $(this).parent().siblings('.title').text();
  console.log(albumId);
  console.log(albumName);

  $.get(url, function(data, status){
    console.log(status);
    if(status === 'success'){
      $('main').html(data);
    }else{
      alertify.error('Something went wrong with your request, try again later');
    }

  });
});

//On click Logout
$(document).on('click', '.logout', function(e){
  e.preventDefault();

  var url = `${domainPath}users/logout`;

  $.get(url, function(data, status){
    if(status === 'success'){
      alertify.success('You are logged out, see you');
      fnNavigate('pages/home');
    }else{
      alertify.error('Something went wrong with your request, try again later');
    }
  })
});


}); // end of document ready
})(jQuery); // end of jQuery name space
