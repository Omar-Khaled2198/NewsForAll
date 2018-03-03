var userChoices=[];
var sourcesClicked=[];

function finding(id)
{
    for(var i=0;i<userChoices.length;i++)
    {
        if(userChoices[i]==id)
            return i;
    }
    return -1;
}
function weather()
{
    var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://api.weatherbit.io/v2.0/current?ip=auto&key=6b29a8ccbffb4e888306ae9f9d69698a",
        "method": "GET",
        "headers": {}
    };
    $.ajax(settings).done(function (response) {
         document.getElementById("weather-icon").src="img/icons/"+response["data"][0].weather.icon+".png";
         console.log(document.getElementById("weather-icon").src);
         var des=response["data"][0].temp+" C"+"<br>"+response["data"][0].city_name;
         document.getElementById("weather-desc").innerHTML=des;
    });
}
function category(id)
{
    document.getElementById("flex").innerHTML="";
    var category=document.getElementById(id);
    var country=["eg","us"];
    for(var i=0;i<2;i++)
    {
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "https://newsapi.org/v2/top-headlines?country="+country[i]+"&category=" + category.value + "&apiKey=3481b84d166847b78345bf6e04b8d8e4",
            "method": "GET",
            "headers": {}
        }
        $.ajax(settings).done(function (response) {
            show(response, 0);
        });
    }
}
function loadSources()
{
    var settings = {
        "async": true,
        "crossDomain": true,
        "url":"https://newsapi.org/v2/sources?&apiKey=3481b84d166847b78345bf6e04b8d8e4",
        "method": "GET",
        "headers": {}
    }
    $.ajax(settings).done(function (response)
    {
        for(var i=0;i<response["sources"].length;i++)
        {
            var source=document.createElement('button');
            source.innerHTML=response["sources"][i].name;
            source.id=response["sources"][i].id;
            source.className="src";
            source.onclick=function () {

                if(finding(this.id)==-1)
                {
                    this.style.background="forestgreen";
                    this.style.color="white";
                    userChoices.push(this.id);

                }
                else
                {
                    this.style.background="";
                    this.style.color="black";
                    sourcesClicked[this.id]=0;
                    var index=finding(this.id);
                    userChoices.splice(index,1);
                }
            }
            document.getElementById("sources").appendChild(source);
        }
    });
}
function filter()
{
    document.getElementById("flex").innerHTML="";
   for(var i=0;i<userChoices.length;i++)
   {
       var settings = {
           "async": true,
           "crossDomain": true,
           "url":"https://newsapi.org/v2/top-headlines?sources="+userChoices[i]+"&apiKey=3481b84d166847b78345bf6e04b8d8e4",
           "method": "GET",
           "headers": {}
       }
       $.ajax(settings).done(function (response)
       {
           show(response,0);
       });
   }
}

function clearing()
{
    for(var i=0;i<userChoices.length;i++)
    {
        document.getElementById(userChoices[i]).style.background="";
        document.getElementById(userChoices[i]).style.color="black";
    }
    userChoices=[];
}

function search()
{
    var q=document.getElementById("search-input").value;
    var settings = {
        "async": true,
        "crossDomain": true,
        "url":"https://newsapi.org/v2/everything?q="+q+"&sortBy=popularity&apiKey=3481b84d166847b78345bf6e04b8d8e4",
        "method": "GET",
        "headers": {}
    }
    $.ajax(settings).done(function (response)
    {

        show(response,1);
    });
}

function load() {

        var settings = {
        "async": true,
        "crossDomain": true,
        "url": "https://newsapi.org/v2/top-headlines?sources=abc-news&apiKey=3481b84d166847b78345bf6e04b8d8e4",
        "method": "GET",
        "headers": {}
        }


    $.ajax(settings).done(function (response)
    {
        show(response,1);
    });
}


function show(response,check)
{
    if(check)document.getElementById("flex").innerHTML="";
    for(var i=0;i<response["articles"].length;i++)
    {
        var a=document.createElement('a');
        a.href=response["articles"][i].url;
        a.target="_blank";
        var theCard=document.createElement('div');
        theCard.className="thecard";
        var cardImg=document.createElement('div');
        cardImg.className="card-img";
        var img=document.createElement('img');
        img.src=response["articles"][i].urlToImage;
        if(response["articles"][i].urlToImage==null)
            img.setAttribute('src', "img/notfound.png");
        var source=document.createElement('span');
        source.className="source";
        source.innerHTML=response["articles"][i].source.name;
        var cardCaption=document.createElement('div');
        cardCaption.className="card-caption";
        var title=document.createElement('h1');
        title.className="title";
        title.innerHTML=response["articles"][i].title;
        var desc=document.createElement('p');
        desc.className="desc";
        desc.innerHTML=response["articles"][i].description;
        var date=document.createElement('div');
        date.className="date";
        var d = new Date(response["articles"][i].publishedAt);
        d.toLocaleDateString().replace(/\//g,'-');
        date.innerHTML=d;
        cardImg.appendChild(img);
        cardImg.appendChild(source);
        theCard.appendChild(cardImg);
        cardCaption.appendChild(title);
        cardCaption.appendChild(desc);
        theCard.appendChild(cardCaption);
        theCard.appendChild(date);
        a.appendChild(theCard);
        document.getElementById("flex").appendChild(a);

    }
}
