$(document).ready(function() {
    songid = 0;
    songList = '';
    window.lrc_obj = '';
    window.playtime = 0;
    url = "player.php";
    $.ajax({
        url: url,
        method: "GET",
        dataType: "json",
        async: false,
        success: function(data) {
            songList = data
        }
    });
    $("#jquery_jplayer_1").jPlayer({
        ready: function() {
            songid = parseInt(Math.random() * (songList.length - 1));
            _play()
        },
        timeupdate: function(obj) {
            if (lrc_obj) {
                show_lrc(obj.jPlayer.status.currentTime)
            }
        },
        ended: function(event) {
            $(this).jPlayer("play");
            window.playtime = 0;
            songid = songid + 1;
            _play()
        },
        solution: "flash",
        swfPath: "Img",
        supplied: "mp3,oga",
        wmode: "window"
    });
    $(".jp-next").on('click', function() {
        songid = songid + 1;
        _play()
    });
    $(".jp-previous").on('click', function() {
        songid = songid - 1;
        _play()
    })
});

function show_lrc(sec) {
    var lrc = '';
    var i = window.playtime;
    if (sec >= window.lrc_obj[i].time) {
        $('#lrc').html(lrc_obj[i].lyric);
        window.playtime++
    }
    if (sec < window.lrc_obj[0].time) {
        $('#lrc').html("音乐播放器");
        window.playtime = 0
    }
}
function _play() {
    if (songid > songList.length - 1) songid = 0;
    if (songid < 0) songid = songList.length - 1;
    var list = songList[songid];
    document.title = list.title + ' - ' + list.artist + ' - 音乐播放器';
    $("#jp-title").html('音乐播放器 -' + ' ' + list.title + ' ' + list.artist);
    $(".poster").attr("src", list.poster);
    $.ajax({
        url: url + '?lrc=' + list.lyric,
        method: "GET",
        dataType: "text",
        async: true,
        success: function(data) {
            var lyric = new LRC({
                lyric: data,
                separator: '<br>',
                txt: '...................'
            });
            if (lyric.jsonLysic.length < 1) {
                window.lrc_obj = lyric.jsonLysic
            } else {
                $('#lrc').html('暂无歌词 -- Jplayer');
                window.lrc_obj = ''
            }
        }
    });
    $("#jquery_jplayer_1").jPlayer("setMedia", {
        mp3: list.mp3
    }).jPlayer("play")
}