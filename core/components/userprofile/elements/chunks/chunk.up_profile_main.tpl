<div class="row userprofile-page">
    <div id="content" class="col-md-8">
        <div class="userprofile-avatar-wrapper">
            <img src="[[+avatar]]" class="userprofile-avatar">
        </div>
        <h3>&nbsp;&nbsp;[[+fullname]]</h3>
        <div class="clearfix"></div>
        <br>
        [[+section]]
        <br>
        [[+content]]
    </div>

    <div id="sidebar" class="col-md-4">
        <div class="sidebar-block">
            <h4 class="title">
                <a href="[[+main_url]]/[[!+user_id]]/">[[+fullname]] </a>
            </h4>

            <div class="row">
                <div class="col-md-4">
                    <div>
                        <i class="glyphicon glyphicon-user"></i>
                        <a href="[[+main_url]]/[[!+user_id]]/">[[%up_profile]]</a>
                    </div>
                    <div style="margin-top:10px;">
                        <i class="glyphicon glyphicon-pencil"></i>
                        <a href="/">Написать</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <i class="black glyphicon glyphicon-cog"></i>
                        <a href="[[+main_url]]/setting/">[[%up_setting]]</a>
                    </div>
                    <div style="margin-top:10px;">
                        <i class="black glyphicon glyphicon-off"></i>
                        <a href="/?action=auth/logout">[[%up_auth_logout]]</a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="userprofile-avatar-wrapper">
                        <img src="[[+avatar]]" class="userprofile-avatar">
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>


        </div>

        <div class="sidebar-block">
            <h4 class="title">Статистика (не работает) </h4>
            <div class="ajax-snippet">
                <table class="table">
                    <thead>
                    <tr>
                        <td>Первый тикет:</td>
                        <td>18.06.2012</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Всего тикетов:</td>
                        <td>[[!+up.total.tickets]]</td>
                    </tr>
                    <tr>
                        <td>Всего комментариев:</td>
                        <td>[[!+up.total.comments]]</td>
                    </tr>
                    <tr>
                        <td>Всего пользователей:</td>
                        <td>2 627</td>
                    </tr>
                    <tr>
                        <td>Тикетов в день:</td>
                        <td>4.1</td>
                    </tr>
                    <tr>
                        <td>Комментариев в день:</td>
                        <td>24.62</td>
                    </tr>
                    <tr>
                        <td>Регистраций в день:</td>
                        <td>2.8</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>