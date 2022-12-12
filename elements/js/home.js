let dispo = (bol) => {
  if (bol == 1) {
    return "<p class= 'text-success'>Disponible</p>";
  } else if (bol == 0) {
    return "<p class= 'text-danger'>Vendu</p>";
  }
};

let time = (UNIX_timestamp, dteformat = null) => {
  var a = new Date(UNIX_timestamp * 1000);
  var months = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
  ];
  var year = a.getFullYear();
  var month = months[a.getMonth()];
  var date = a.getDate();

  if (dteformat == "-") {
    return year + "-" + a.getMonth() + "-" + date;
  }
  var time = date + " " + month + " " + year;
  return time;
};

$(document).ready(() => {
  //------ ajax
  let ajax = (optioin, result) => {
    $.get(
      "server/seepost/index.php", // Un script PHP que l'on va créer juste après
      optioin,
      result,
      "text"
    );
  };

  ///--------------- func post
  let postfunc = (data) => {
    data = JSON.parse(data);
    $("#home-produits").html(
      ' <h2 class="text-center">Produits </h2>  <div id="posts"> </div> '
    );

    for (let i = 0; i < data.length; i++) {
      let post = data[i];

      let $box = $("<div class='post '>").html(
        ' <div class="partentIMG"> <img src="' +
          post.miniature +
          ' " ></div> <h3 class="text-center">' +
          post.name +
          '</h3> <p class="description" >' +
          $("<p>").html(post.description).text() +
          " </p>  <p> Prix : <strong> " +
          post.prix +
          post.monnais +
          " </strong> </p><pan id='PPD-" +
          post.id +
          "'>   " +
          dispo(post.disponible) +
          '</span>  <p class="date" > Publié le :  <time datetime="' +
          time(post.p_date, "-") +
          '"> ' +
          time(post.p_date) +
          ' </time></p>  <a href="article_' +
          post.slug +
          '" class=" btn btn-primary ">Voir Le Produit </a> '
      );

      if (window.location.href.includes("/admin")) {
        let $admin = $("<span>").html(
          '<hr> <strong id="idPostNumber">ID:  ' +
            post.id +
            '</p> </strong> <hr>   <a href="#DeleteObj" id="DeletPost-' +
            post.id +
            '" class = "btn text-danger " >  Supprimer </a >  <a href="#EditPost" id="EditPost-' +
            post.id +
            '"  class = "btn text-primary"> ' +
            $(dispo(post.disponible)).text() +
            " </a>   "
        );
        $admin.addClass("my-3");

        $admin.appendTo($box);
      }

      $box.appendTo("#posts");
    }
  };

  //-------------- option POst

  ///----------      post

  ajax({ table: "post" }, postfunc);

  /// Categorie
  ajax(
    {
      table: "categorie",
    },
    (data) => {
      data = JSON.parse(data);
      $("#home-categories").html(
        ' <h2 id="post" class="text-center ">Catégories </h2> <ul id="categs">  </ul> '
      );

      for (let i = 0; i < data.length; i++) {
        let categ = data[i];

        let $box = $("<li>").html(
          ' <a href="#' +
            categ.slug +
            '" id="' +
            categ.id +
            ' "   > ' +
            categ.name +
            "</a>"
        );

        if (window.location.href.includes("/admin")) {
          let $admin = $("<span>").html(
            " <p >SLUG:  " +
              categ.slug +
              '</p>  <a href="#DeleteObj" id="DeletCtg-' +
              categ.id +
              '" class = " btn text-danger " > SUpprimer </a><a href = "#EditCategorie" id = "EditCtg-' +
              categ.id +
              '"  class = "btn text-primary"> Editer </a> <hr>'
          );

          $($box).children().remove();
          $admin.appendTo($box);
        }

        $box.appendTo("#categs");
      }
    }
  );

  $("input , select ").change((e) => {
    e.preventDefault();

    ajax(
      {
        table: $("#home-categories h2").attr("id"),
        id_categ: $("#home-categories ul li a.actif ").attr("id"),
        q: $("#q").val(),
        order: $("#order").val(),
        dir: $("#dir").val(),
        min: $("#min").val(),
        max: $("#max").val(),
        devise: $("#devise").val(),
      },
      postfunc
    );
  });

  setTimeout(() => {
    $("#nx , #pv ").click((e) => {
      e.preventDefault();

      let v = $(e.target).attr("href").replace("#", "");

      if (
        v <= 0 ||
        ($(e.target).attr("id") == "nx" && $("#posts div").length < 30)
      ) {
        return false;
      }
      $("#nx").attr("href", "#" + (+v + 30));
      $("#pv").attr("href", "#" + (+v - 30));

      ajax(
        {
          id_categ: (categ = $("#home-categories ul li .actif").attr("id")),
          v: v,
          table: $("#home-categories h2").attr("id"),
          id_categ: $("#home-categories ul li a.actif ").attr("id"),
          q: $("#q").val(),
          order: $("#order").val(),
          dir: $("#dir").val(),
          min: $("#min").val(),
          max: $("#max").val(),
          devise: $("#devise").val(),
        },
        postfunc
      );
    });

    // *Si on click sur une categ

    //--- func select Categorie
    let selectCategorie = (e, $e = null) => {
      // e.preventDefault();
      if (e != null) {
        var $this = $(e.target);
      } else if ($e != null) {
        var $this = $('#home-categories ul li a[href^="#' + $e + '"] ');
        // var $this = $('#home-categories ul li a ')[1];
        // [href$='
      }

      $(" #home-categories ul li .actif").removeClass("actif");
      $($this).addClass("actif");
      let categ = $($this).attr("id");

      $("#home-categories h2").attr("id", "categorie_post");

      ajax(
        {
          id_categ: $($this).attr("id"),
          v: +($("a#nx").attr("href").replace("#", "") - 30),
          table: "categorie_post",
          q: $("#q").val(),
          order: $("#order").val(),
          dir: $("#dir").val(),
          min: $("#min").val(),
          max: $("#max").val(),
          devise: $("#devise").val(),
        },
        postfunc
      );
    };
    if (
      !window.location.href.includes("/admin") &&
      window.location.href.includes("#")
    ) {
      let $e = window.location.href.split("home#")[1];

      selectCategorie(null, $e);
    }

    $(" #home-categories ul li a ").click((e) => {
      selectCategorie(e);
    });
  }, 1000);
});
