{if $can_comment == 1 AND !$is_logged_in}
    <span class="title-comment"><b>{sprintf(lang('Пожалуйста, войдите для комментирования', 'newLevel'), site_url($modules.auth))}</b></span>
    <button type="button" data-trigger="#loginButton">
        <span class="text-el d_l_1">{lang('Войти','newLevel')}</span>
    </button>
{/if}
<div class="comments" id="comments">
    <div class="title-comment">{lang('Отзывы', 'newLevel')} {if $visibleMainForm === false || $visibleMainForm == NULL}<button data-drop=".comments-main-form" data-place="inherit" data-overlay-opacity="0" data-after="Comments.toComment" class="m-l_5"><span class="ref2 f-w_b">+</span> <span class="d_l_3">{lang('Добавить отзыв', 'newLevel')}</span></button>{/if}</div>
            {if $can_comment == 0 OR $is_logged_in}
        <div class="drop comments-main-form {if !$comments_arr}noComments{/if} {if $visibleMainForm || $visibleMainForm == NULL}active inherit{/if}" {if $visibleMainForm}style="display: block;"{/if}>
            <div class="frame-comments layout-highlight horizontal-form">
                <!-- Start of new comment fild -->
                <div class="form-comment main-form-comments">
                    <div class="inside-padd">
                        <form method="post">
                            {if !$is_logged_in}
                                {if $use_moderation}
                                    <label class="d_n succ">
                                        <span class="frame-form-field">
                                            <div class="msg">
                                                <div class="success">
                                                    {lang('Комментарий будет отправлен на модерацию','newLevel')}
                                                </div>
                                            </div>
                                        </span>
                                    </label>
                                {/if}
                                <div class="clearfix">
                                    <label style="width: 40%;float: left;">
                                        <span class="title">{lang('Ваше имя','newLevel')}</span>
                                        <span class="frame-form-field">
                                            <input type="text" name="comment_author" value="{get_cookie('comment_author')}"/>
                                        </span>
                                    </label>
                                    <label style="width: 60%;float: left;">
                                        <span class="title t-a_r">{lang('E-mail', 'newLevel')}</span>
                                        <span class="frame-form-field">
                                            <input type="text" name="comment_email" id="comment_email" value="{get_cookie('comment_email')}"/>
                                        </span>
                                    </label>
                                </div>
                            {/if}
                            <label>
                                <span class="title">{lang('Отзыв')}</span>
                                <span class="frame-form-field">
                                    <textarea name="comment_text" class="comment_text">{$_POST.comment_text}</textarea>
                                </span>
                            </label>
                            <!-- End star reiting -->
                            {if $use_captcha}
                                <div class="frame-label m-b_10">
                                    <span class="title">{lang('Код защиты')}:</span>
                                    <div class="clearfix">
                                        <div class="m-b_10 m-t_5 f_l">
                                            {$cap_image}
                                        </div>
                                        <div class="frame-form-field o_h">
                                            <input type="text" name="captcha" id="captcha" class="m-t_5"/>
                                        </div>
                                    </div>
                                </div>
                            {/if}
                            <!-- Start star reiting -->
                            <div class="frame-label">
                                <span class="title f_l t-a_l">{lang('Оценка', 'newLevel')}</span>
                                <div class="frame-form-field">
                                    <div class="star f_l">
                                        <div class="productRate star-big clicktemprate">
                                            <div class="for_comment" style="width: 0%"></div>
                                            <input class="ratec" name="ratec" type="hidden" value=""/>
                                        </div>
                                    </div>
                                    <div class="btn-form f_r">
                                        <input type="submit" value="{lang('Оставить отзыв')}" onclick="Comments.post(this, {literal}{'visibleMainForm': '1'}{/literal})"/>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- End of new comment fild -->
                </div>
            </div>
        </div>
    {/if}
    {if $comments_arr}
        <div class="frame-list-comments">
            <ul class="sub-1 product-comment patch-product-view">
                {foreach $comments_arr as $key => $comment}
                    <li>
                        <input type="hidden" name="comment_item_id" value="{$comment['id']}"/>
                        <div class="clearfix global-frame-comment-sub1">
                            <div class="author-data-comment author-data-comment-sub1">
                                <span class="f-s_0 frame-autor-comment"><span class="icon_comment"></span><span class="author-comment">{$comment.user_name}</span></span>
                                <span class="date-comment">
                                    <span class="day">{echo date("d", $comment.date)} </span>
                                    <span class="month">{echo month(date("n", $comment.date))} </span>
                                    <span class="year">{echo date("Y ", $comment.date)}</span>
                                </span>
                                {if $comment.rate != 0}
                                    <div class="mark-pr">
                                        <span class="title">{lang('Оценка товара','newLevel')}:</span>
                                        <div class="star-small d_i-b">
                                            <div class="productRate star-small">
                                                <div style="width: {echo (int)$comment.rate *20}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                {/if}
                            </div>
                            <div class="frame-comment-sub1">
                                <div class="frame-comment">
                                    <p>{$comment.text}</p>
                                    {if $comment.text_plus != Null}
                                        <p>
                                            <b>{lang('Да', 'newLevel')}</b><br>
                                            {$comment.text_plus}
                                        </p>
                                    {/if}
                                    {if $comment.text_minus != Null}
                                        <p>
                                            <b>{lang('Нет', 'newLevel')}</b><br>
                                            {$comment.text_minus}
                                        </p>
                                    {/if}
                                </div>
                                <div class="footer-comment clearfix">
                                    {if $can_comment == 0 OR $is_logged_in}
                                        <div class="btn f_l">
                                            <button type="button" data-rel="cloneAddPaste" data-parid="{$comment['id']}">
                                                <span class="text-el d_l_3">{lang('Ответить')}</span>
                                            </button>
                                        </div>
                                    {/if}
                                    <div class="frame-mark f_r">
                                        <div class="func-button-comment">
                                            <span class="s-t">{lang('Отзыв полезен?','newLevel')}</span>
                                            <span class="btn like">
                                                <button type="button" class="usefullyes" data-comid="{echo $comment.id}">
                                                    <span class="icon_like"></span>
                                                    <span class="text-el d_l_3">{lang('Да','newLevel')}</span>
                                                </button>
                                            </span>
                                            <span class="btn dis-like">
                                                <button type="button" class="usefullno" data-comid="{echo $comment.id}">
                                                    <span class="icon_dislike"></span>
                                                    <span class="text-el d_l_4">{lang('Нет','newLevel')}</span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {$countAnswers = $CI->load->module('comments')->commentsapi->getCountCommentAnswersByCommentId($comment.id)}
                        {if $countAnswers}
                            <ul class="frame-list-comments sub-2">
                                {foreach $comment_ch as $com_ch}
                                    {if $com_ch.parent == $comment.id}
                                        <li>
                                            <div class="global-frame-comment-sub2">
                                                <div class="author-data-comment author-data-comment-sub2">
                                                    <span class="author-comment">{$com_ch.user_name}</span>
                                                    <span class="date-comment">
                                                        <span class="day">{echo date("d", $comment.date)} </span>
                                                        <span class="month">{echo month(date("n", $comment.date))} </span>
                                                        <span class="year">{echo date("Y ", $comment.date)}</span>
                                                    </span>
                                                </div>
                                                <div class="frame-comment-sub2">
                                                    <div class="frame-comment">
                                                        <p>
                                                            {$com_ch.text}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    {/if}
                                {/foreach}
                            </ul>
                        {/if}
                        <div class="btn-all-comments">
                            <button type="button"><span class="text-el" data-hide='<span class="d_l_1">{lang('Скрыть','newLevel')}</span> ↑' data-show='<span class="d_l_1">{lang('Смотреть все ответы','newLevel')}</span> ↓'></span></button>
                        </div>
                    </li>
                {/foreach}
            </ul>
            <button class="t-d_n f-s_0 s-all-d ref2 d_n_" data-trigger="[data-href='#comment']" data-scroll="true">
                <span class="icon_arrow"></span>
                <span class="text-el">{lang('Смотреть все ответы','newLevel')}</span>
            </button>
        </div>
    {/if}

    <div class="frame-drop-comment" data-rel="whoCloneAddPaste">
        <div class="form-comment layout-highlight frame-comments">
            <div class="inside-padd horizontal-form">
                <form>
                    <label class="err-label">
                        <span class="frame-form-field">
                            <div class="frame-label error" name="error_text"></div>
                        </span>
                    </label>

                    {if !$is_logged_in}
                        <label>
                            <span class="title">{lang('Ваше имя', 'newLevel')}</span>
                            <span class="frame-form-field">
                                <input type="text" name="comment_author" value="{get_cookie('comment_author')}"/>
                            </span>
                        </label>
                        <label>
                            <span class="title">{lang('Email', 'newLevel')} </span>
                            <span class="frame-form-field">
                                <input type="text" name="comment_email" value="{get_cookie('comment_email')}"/>
                            </span>
                        </label>
                        <label class="d_n succ">
                            <span class="frame-form-field">
                                <div class="msg">
                                    <div class="success">
                                        {lang('Комментарий будет отправлен на модерацию','newLevel')}
                                    </div>
                                </div>
                            </span>
                        </label>
                    {/if}
                    <label>
                        <span class="title">{lang('Ответ','newLevel')}</span>
                        <span class="frame-form-field">
                            <textarea class="comment_text" name="comment_text"></textarea>
                        </span>
                    </label>
                    <div class="frame-label">
                        <span class="frame-form-field">
                            <input type="hidden" id="parent" name="comment_parent" value="">
                            <span class="btn-form">
                                <input type="submit" value="{lang('Комментировать', 'newLevel')}" onclick="Comments.post(this)"/>
                            </span>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>