userChoices=[];
function finding(id)
{
    for(var i=0;i<userChoices.length;i++)
    {
        if(userChoices[i]==id)
            return i;
    }
    return -1;
}

$(document).on("click", ".src", function(){
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
        var index=finding(this.id);
        userChoices.splice(index,1);
    }
});


function clearing()
{
    for(var i=0;i<userChoices.length;i++)
    {
        document.getElementById(userChoices[i]).style.background="";
        document.getElementById(userChoices[i]).style.color="black";
    }
    userChoices=[];
}