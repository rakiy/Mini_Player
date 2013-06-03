/**
 *
 * LRC歌词解析
 * @param string lyric      文本格式歌词
 * @param string separator  分隔符 
 * @param string txt        歌词空白时显示的文字
 * @return json
 * @author  				Rakiy[Xux851@Gmail.Com]
 *
 * 使用方法：var lyric = new LRC({lyric:data,separator:'<br>',txt:'这里可以插入文字'});
 * lyric.jsonLysic          返回的JSON格式歌词
 * lyric.IsLyricValid       判断是否为LRC格式歌词
 *
 */
/**
 *
 * LRC歌词解析
 * @param string lyric      文本格式歌词
 * @param string separator  分隔符 
 * @param string txt        歌词空白时显示的文字
 * @return json
 *
 * 使用方法：var lyric = new LRC({lyric:data,separator:'<br>',txt:'这里可以插入文字'});
 * lyric.jsonLysic          返回的JSON格式歌词
 * lyric.IsLyricValid       判断是否为LRC格式歌词
 *
 */
LRC=function(){this.initialize.apply(this,arguments);}
LRC.prototype={
    arrLyric:[],
    jsonLyric:{},
    initialize:function(arg){
        this.arrLyric=[];			//输出数组
        this.jsonLyric={};
        this.separator=arg.separator;	//分隔符
        this.txt = arg.txt;				//空歌词时默认文本
        this._init(arg.lyric);
        this.LrcArray=this.sort(this.arrLyric);
        this.jsonLysic = this.Arr2Json(this.LrcArray);
    },
    _init:function(lyric){
        var lrc_re=new RegExp('\[[0-9:.]*\]','g');//g全局标志，获取所有匹配结果必须
        var lrc_arr=lyric.split(this.separator);
        var lrc_temp=0;
        for(var i=0;i<lrc_arr.length;i++){
            while((lrc_result = lrc_re.exec(lrc_arr[i])) != null){
                var lrc_second=this.parseSecond(lrc_result.toString().replace(/\[|\]/g,''));
                var lrc_txt=this.Trim(lrc_arr[i].replace(/\[[\w\W]*\]/g,''));//add to lyric text array
                if(lrc_txt==''){lrc_txt = this.txt}     
                if(!isNaN(lrc_second)){
        			this.arrLyric[lrc_temp++] = lrc_second + '|' + lrc_txt;
	            }
       		}
       		
       	}
    },
    parseSecond:function(time){
        try{
            var lrc_arr=time.split(':');//time格式为时间格式 00:00
            return parseInt(lrc_arr[0])*60+parseFloat(lrc_arr[1]);
        }catch(ex){
            return 0;
        }
    },
    sort:function(arrLyric){
    	var jsonLyric = {};
        for(var i=0;i<arrLyric.length-1;i++){
            for(var ii=i+1;ii<arrLyric.length;ii++){
                var lrc_cur=parseFloat(arrLyric[i].split('|')[0]);
                var lrc_next=parseFloat(arrLyric[ii].split('|')[0]);
                if(lrc_cur>lrc_next){
                    var lrc_temp  = arrLyric[i];
                    arrLyric[i]   = arrLyric[ii];
                    arrLyric[ii]  = lrc_temp;
                }
            }
        }    
        return arrLyric;
    },
    Trim:function(str){	
    	return str.replace(/^\s*|\s*$/g,"");
	},
	Arr2Json:function(arr){
		var jsonLyric={};
		for(var i=0;i<arr.length-1;i++){
			jsonLyric[i] = {"time":arr[i].split('|')[0],"lyric":arr[i].split('|')[1]};
		}
		return jsonLyric;
	}
}