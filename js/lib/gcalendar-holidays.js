/**
 *  GCalendar Holidays - Googleカレンダーから日本の祝日を取得
 *  @see       http://0-oo.net/sbox/javascript/google-calendar-holidays
 *  @version   0.4.0
 *  @copyright 2008-2011 dgbadmin@gmail.com
 *  @license   http://0-oo.net/pryn/MIT_license.txt (The MIT license)
 *
 *  See also
 *  @see http://code.google.com/intl/ja/apis/calendar/data/2.0/reference.html
 *  @see http://code.google.com/intl/ja/apis/gdata/docs/json.html
 */
var GCalHolidays = {
    /** GoogleカレンダーのID（複数でも可） */
    userIds: [
        "japanese__ja@holiday.calendar.google.com"  //Google公式版
        //"japanese@holiday.calendar.google.com"    //Google公式（英語）版
        //"outid3el0qkcrsuf89fltf7a4qbacgt9@import.calendar.google.com" //mozilla.org版
    ],

    /** 取得するイベント（スケジュール）の最大件数 */
    maxResults: 31,

    // @see http://code.google.com/intl/ja/apis/calendar/data/2.0/reference.html#Visibility
    visibility: "public",

    // @see http://code.google.com/intl/ja/apis/calendar/data/2.0/reference.html#Projection
    projection: "full-noattendees",

    /** jQuery UI Datepicker用のstyle（Themeに合わせてお好みで上書きして使う） */
    datepickerStyles: {
        sunday:   "background-image: none; background-color: #f99", //日曜日
        saturday: "background-image: none; background-color: #6cf", //土曜日
        holiday:  "background-image: none; background-color: #f9f"  //祝日
    }
};
/**
 *  祝日を取得する
 *  @param  Function    callback    データ取得時に呼び出されるfunction
 *  @param  Number      year        (optional) 年（指定しなければ今年）
 *  @param  Number      month       (optional) 月（1～12 指定しなければ1年の全て）
 */
GCalHolidays.get = function(callback, year, month) {
    //日付範囲
    var padZero = function(value) { return ("0" + value).slice(-2); };
    var y = year || new Date().getFullYear();
    var start = [y, padZero(month || 1), "01"].join("-");
    var m = month || 12;
    var end = [y, padZero(m), padZero(new Date(y, m, 0).getDate())].join("-");

    this._caches = (this._caches || {});
    this._userCallback = callback;

    for (var i = 0, len = this.userIds.length; i < len; i++) {
        var cache = this._caches[i + ":" + start + ".." + end];

        if (cache) {    //取得済みの場合はそれを使う
            callback(cache, i);
            continue;
        }

        //URL作成
        var url = location.protocol + "//www.google.com/calendar/feeds/";
        url += encodeURIComponent(this.userIds[i]) + "/";
        url += this.visibility + "/" + this.projection;
        url += "?alt=json-in-script&callback=GCalHolidays.decode";
        url += "&max-results=" + this.maxResults;
        url += "&start-min=" + start + "&start-max=" + end + "T23:59:59";

        //scriptタグ生成
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = url;
        script.charset = "UTF-8";
        document.body.appendChild(script);
    }
};
/**
 *  JSONPによりGoogle Calendar API経由で呼び出されるfunction
 *  @param  Object  gdata   カレンダーデータ
 */
GCalHolidays.decode = function(gdata) {
    var days = GCalHolidays._entries2days(gdata.feed.entry);

    var links = gdata.feed.link;
    var href = "";

    for (var j = 0, len2 = links.length; j < len2; j++) {
        if (links[j].rel == "self") {
            href = links[j].href;
        }
    }

    var userId = decodeURIComponent(href.split("/")[5]);
    var index = null;

    //どのカレンダーか特定する
    for (var i = 0, len = this.userIds.length; i < len; i++) {
        if (this.userIds[i] == userId) {
            index = i;
            break;
        }
    }

    //キャッシュする
    var range = href.match(/\d{4}-\d{2}-\d{2}/g);   //日付範囲の最初の日と最後の日
    this._caches[index + ":" + range[0] + ".." + range[1]] = days;

    //コールバック
    this._userCallback(days, index);
};
/**
 *  JSONPで取得したデータから日付情報を取り出す
 *  @param  Array   entries スケジュール
 *  @return Array   日付情報（year, month, date, title）
 */
GCalHolidays._entries2days = function(entries) {
    var days = [];

    if (!entries) {
        return days;
    }

    //日付順にソート
    entries.sort(function(a, b) {
        return (a.gd$when[0].startTime > b.gd$when[0].startTime) ? 1 : -1;
    });

    //シンプルな器に移す
    for (var i = 0, len = entries.length; i < len; i++) {
        var ymd = entries[i].gd$when[0].startTime.split("T")[0].split("-");
        var title = entries[i].title.$t;
        //年月日は使いやすいように数値にする
        days[i] = { year: ymd[0] * 1, month: ymd[1] * 1, date: ymd[2] * 1, title: title };
    }

    return days;
};

/**
 *  jQuery UI Datepickerのカレンダーに祝日を表示する
 *  @param  Number  year    表示する年
 *  @param  Number  month   表示する月
 *  @param  Object  inst    Datepicker
 *  @see http://jqueryui.com/demos/datepicker/
 */
GCalHolidays.datepicker = function(year, month, inst) {
    setTimeout(function() { //処理後に対象のdivが再構築されるケースを回避
        GCalHolidays.get(function(holidays) {
            for (var i = 0, len = holidays.length; i < len; i++) {
                var holiday = holidays[i];

                if (holiday.year != year || holiday.month != month) {
                    return; //既に別の月を表示している場合は何もしない
                }

                inst.dpDiv.find("a").each(function() {
                    if ($(this).text() == holiday.date) {
                        $(this).addClass("gcal-holiday").attr("title", holiday.title);
                        return false;
                    }
                });
            }
        }, year, month);
    }, 1);
};
/**
 *  jQuery UI Datepickerが有効な場合はイベントハンドラとstyleをセットする
 */
if (window.$ && $.datepicker && $.datepicker.setDefaults) {
    $.datepicker.setDefaults({
        beforeShow: function(input, inst) {
            var date = $(input).datepicker("getDate") || new Date();
            GCalHolidays.datepicker(date.getFullYear(), date.getMonth() + 1, inst);
        },
        beforeShowDay: function(date) { //土日のclass属性
            return [true, { 0: "gcal-sunday", 6: "gcal-saturday" }[date.getDay()] || ""];
        },
        onChangeMonthYear: GCalHolidays.datepicker
    });

    $(function() {  //ページ表示後に土日・祝日用のstyleをセット
        var styles = GCalHolidays.datepickerStyles;
        var css = "";
        css += ".gcal-sunday   .ui-state-default { " + styles.sunday + " } ";
        css += ".gcal-saturday .ui-state-default { " + styles.saturday + " } ";
        css += ".ui-widget-content .gcal-holiday { " + styles.holiday + " }";
        $("head").append($('<style type="text/css">' + css + "</style>"));
    });
}
