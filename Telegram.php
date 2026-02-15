<?php

if (file_exists('TelegramErrorLogger.php')) {
    require_once 'TelegramErrorLogger.php';
}

/**
 * Telegram Bot Class.
 * @see https://core.telegram.org/bots/api
 */
class Telegram
{
    // Update Types
    const INLINE_QUERY = 'inline_query';
    const CALLBACK_QUERY = 'callback_query';
    const EDITED_MESSAGE = 'edited_message';
    const REPLY = 'reply';
    const MESSAGE = 'message';
    const PHOTO = 'photo';
    const VIDEO = 'video';
    const AUDIO = 'audio';
    const VOICE = 'voice';
    const DOCUMENT = 'document';
    const ANIMATION = 'animation';
    const LOCATION = 'location';
    const CONTACT = 'contact';
    const CHANNEL_POST = 'channel_post';
    const EDITED_CHANNEL_POST = 'edited_channel_post';
    const BUSINESS_CONNECTION = 'business_connection';
    const BUSINESS_MESSAGE = 'business_message';
    const EDITED_BUSINESS_MESSAGE = 'edited_business_message';
    const DELETED_BUSINESS_MESSAGES = 'deleted_business_messages';
    const MESSAGE_REACTION = 'message_reaction';
    const MESSAGE_REACTION_COUNT = 'message_reaction_count';
    const CHAT_BOOST = 'chat_boost';
    const REMOVED_CHAT_BOOST = 'removed_chat_boost';
    const PURCHASED_PAID_MEDIA = 'purchased_paid_media';
    const POLL = 'poll';
    const POLL_ANSWER = 'poll_answer';
    const MY_CHAT_MEMBER = 'my_chat_member';
    const CHAT_MEMBER = 'chat_member';
    const CHAT_JOIN_REQUEST = 'chat_join_request';
    const CHAT_OWNER_LEFT = 'chat_owner_left';
    const CHAT_OWNER_CHANGED = 'chat_owner_changed';
    const SUGGESTED_POST_APPROVED = 'suggested_post_approved';
    const SUGGESTED_POST_APPROVAL_FAILED = 'suggested_post_approval_failed';
    const SUGGESTED_POST_DECLINED = 'suggested_post_declined';
    const SUGGESTED_POST_PAID = 'suggested_post_paid';
    const SUGGESTED_POST_REFUNDED = 'suggested_post_refunded';

    private $bot_token = '';
    private $data = [];
    private $updates = [];
    private $log_errors;
    private $proxy;

    /**
     * @param string $bot_token
     * @param bool $log_errors
     * @param array $proxy ['url' => string, 'port' => int, 'type' => CURLPROXY_HTTP, 'auth' => 'user:pass']
     * @see https://core.telegram.org/bots/api
     */
    public function __construct($bot_token, $log_errors = true, array $proxy = [])
    {
        $this->bot_token = $bot_token;
        $this->data = $this->getData();
        $this->log_errors = $log_errors;
        $this->proxy = $proxy;
    }

    /**
     * @param string $api
     * @param array $content
     * @param bool $post
     * @return array|null
     * @see https://core.telegram.org/bots/api#available-methods
     */
    public function endpoint($api, array $content, $post = true)
    {
        $url = 'https://api.telegram.org/bot' . $this->bot_token . '/' . $api;
        $reply = $this->sendAPIRequest($url, $content, $post);
        return json_decode($reply, true);
    }

    /**
     * @return array
     * @see https://core.telegram.org/bots/api#getme
     */
    public function getMe()
    {
        return $this->endpoint('getMe', [], false);
    }

    /**
     * @return string
     */
    public function respondSuccess()
    {
        http_response_code(200);
        return json_encode(['status' => 'success']);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendmessage
     */
    public function sendMessage(array $content)
    {
        return $this->endpoint('sendMessage', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#forwardmessage
     */
    public function forwardMessage(array $content)
    {
        return $this->endpoint('forwardMessage', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendphoto
     */
    public function sendPhoto(array $content)
    {
        return $this->endpoint('sendPhoto', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendaudio
     */
    public function sendAudio(array $content)
    {
        return $this->endpoint('sendAudio', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#senddocument
     */
    public function sendDocument(array $content)
    {
        return $this->endpoint('sendDocument', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendvideo
     */
    public function sendVideo(array $content)
    {
        return $this->endpoint('sendVideo', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendanimation
     */
    public function sendAnimation(array $content)
    {
        return $this->endpoint('sendAnimation', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendvoice
     */
    public function sendVoice(array $content)
    {
        return $this->endpoint('sendVoice', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendvideonote
     */
    public function sendVideoNote(array $content)
    {
        return $this->endpoint('sendVideoNote', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendmediagroup
     */
    public function sendMediaGroup(array $content)
    {
        return $this->endpoint('sendMediaGroup', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendlocation
     */
    public function sendLocation(array $content)
    {
        return $this->endpoint('sendLocation', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendvenue
     */
    public function sendVenue(array $content)
    {
        return $this->endpoint('sendVenue', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendcontact
     */
    public function sendContact(array $content)
    {
        return $this->endpoint('sendContact', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendpoll
     */
    public function sendPoll(array $content)
    {
        return $this->endpoint('sendPoll', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#senddice
     */
    public function sendDice(array $content)
    {
        return $this->endpoint('sendDice', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendchataction
     */
    public function sendChatAction(array $content)
    {
        return $this->endpoint('sendChatAction', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getuserprofilephotos
     */
    public function getUserProfilePhotos(array $content)
    {
        return $this->endpoint('getUserProfilePhotos', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getfile
     */
    public function getFile(array $content)
    {
        return $this->endpoint('getFile', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#kickchatmember
     * @deprecated Use banChatMember
     */
    public function kickChatMember(array $content)
    {
        return $this->endpoint('kickChatMember', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#banchatmember
     */
    public function banChatMember(array $content)
    {
        return $this->endpoint('banChatMember', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#unbanchatmember
     */
    public function unbanChatMember(array $content)
    {
        return $this->endpoint('unbanChatMember', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#restrictchatmember
     */
    public function restrictChatMember(array $content)
    {
        return $this->endpoint('restrictChatMember', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#promotechatmember
     */
    public function promoteChatMember(array $content)
    {
        return $this->endpoint('promoteChatMember', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setchatadministratorcustomtitle
     */
    public function setChatAdministratorCustomTitle(array $content)
    {
        return $this->endpoint('setChatAdministratorCustomTitle', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#banchatsenderchat
     */
    public function banChatSenderChat(array $content)
    {
        return $this->endpoint('banChatSenderChat', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#unbanchatsenderchat
     */
    public function unbanChatSenderChat(array $content)
    {
        return $this->endpoint('unbanChatSenderChat', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setchatpermissions
     */
    public function setChatPermissions(array $content)
    {
        return $this->endpoint('setChatPermissions', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#exportchatinvitelink
     */
    public function exportChatInviteLink(array $content)
    {
        return $this->endpoint('exportChatInviteLink', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#createchatinvitelink
     */
    public function createChatInviteLink(array $content)
    {
        return $this->endpoint('createChatInviteLink', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#editchatinvitelink
     */
    public function editChatInviteLink(array $content)
    {
        return $this->endpoint('editChatInviteLink', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#revokechatinvitelink
     */
    public function revokeChatInviteLink(array $content)
    {
        return $this->endpoint('revokeChatInviteLink', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#approvechatjoinrequest
     */
    public function approveChatJoinRequest(array $content)
    {
        return $this->endpoint('approveChatJoinRequest', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#declinechatjoinrequest
     */
    public function declineChatJoinRequest(array $content)
    {
        return $this->endpoint('declineChatJoinRequest', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setchatphoto
     */
    public function setChatPhoto(array $content)
    {
        return $this->endpoint('setChatPhoto', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#deletechatphoto
     */
    public function deleteChatPhoto(array $content)
    {
        return $this->endpoint('deleteChatPhoto', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setchattitle
     */
    public function setChatTitle(array $content)
    {
        return $this->endpoint('setChatTitle', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setchatdescription
     */
    public function setChatDescription(array $content)
    {
        return $this->endpoint('setChatDescription', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#pinchatmessage
     */
    public function pinChatMessage(array $content)
    {
        return $this->endpoint('pinChatMessage', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#unpinchatmessage
     */
    public function unpinChatMessage(array $content)
    {
        return $this->endpoint('unpinChatMessage', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#unpinallchatmessages
     */
    public function unpinAllChatMessages(array $content)
    {
        return $this->endpoint('unpinAllChatMessages', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#leavechat
     */
    public function leaveChat(array $content)
    {
        return $this->endpoint('leaveChat', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getchat
     */
    public function getChat(array $content)
    {
        return $this->endpoint('getChat', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getchatadministrators
     */
    public function getChatAdministrators(array $content)
    {
        return $this->endpoint('getChatAdministrators', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getchatmembercount
     */
    public function getChatMemberCount(array $content)
    {
        return $this->endpoint('getChatMemberCount', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getchatmember
     */
    public function getChatMember(array $content)
    {
        return $this->endpoint('getChatMember', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setchatstickerset
     */
    public function setChatStickerSet(array $content)
    {
        return $this->endpoint('setChatStickerSet', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#deletechatstickerset
     */
    public function deleteChatStickerSet(array $content)
    {
        return $this->endpoint('deleteChatStickerSet', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getforumtopiciconstickers
     */
    public function getForumTopicIconStickers()
    {
        return $this->endpoint('getForumTopicIconStickers', [], false);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#createforumtopic
     */
    public function createForumTopic(array $content)
    {
        return $this->endpoint('createForumTopic', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#editforumtopic
     */
    public function editForumTopic(array $content)
    {
        return $this->endpoint('editForumTopic', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#closeforumtopic
     */
    public function closeForumTopic(array $content)
    {
        return $this->endpoint('closeForumTopic', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#reopenforumtopic
     */
    public function reopenForumTopic(array $content)
    {
        return $this->endpoint('reopenForumTopic', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#deleteforumtopic
     */
    public function deleteForumTopic(array $content)
    {
        return $this->endpoint('deleteForumTopic', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#unpinallforumtopicmessages
     */
    public function unpinAllForumTopicMessages(array $content)
    {
        return $this->endpoint('unpinAllForumTopicMessages', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#editgeneralforumtopic
     */
    public function editGeneralForumTopic(array $content)
    {
        return $this->endpoint('editGeneralForumTopic', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#closegeneralforumtopic
     */
    public function closeGeneralForumTopic(array $content)
    {
        return $this->endpoint('closeGeneralForumTopic', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#reopengeneralforumtopic
     */
    public function reopenGeneralForumTopic(array $content)
    {
        return $this->endpoint('reopenGeneralForumTopic', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#hidegeneralforumtopic
     */
    public function hideGeneralForumTopic(array $content)
    {
        return $this->endpoint('hideGeneralForumTopic', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#unhidegeneralforumtopic
     */
    public function unhideGeneralForumTopic(array $content)
    {
        return $this->endpoint('unhideGeneralForumTopic', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendmessagedraft
     */
    public function sendMessageDraft(array $content)
    {
        return $this->endpoint('sendMessageDraft', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#answershippingquery
     */
    public function answerShippingQuery(array $content)
    {
        return $this->endpoint('answerShippingQuery', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#answerprecheckoutquery
     */
    public function answerPreCheckoutQuery(array $content)
    {
        return $this->endpoint('answerPreCheckoutQuery', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendinvoice
     */
    public function sendInvoice(array $content)
    {
        return $this->endpoint('sendInvoice', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#createinvoicelink
     */
    public function createInvoiceLink(array $content)
    {
        return $this->endpoint('createInvoiceLink', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendpaidmedia
     */
    public function sendPaidMedia(array $content)
    {
        return $this->endpoint('sendPaidMedia', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#refundstarpayment
     */
    public function refundStarPayment(array $content)
    {
        return $this->endpoint('refundStarPayment', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setmyprofilephoto
     */
    public function setMyProfilePhoto(array $content)
    {
        return $this->endpoint('setMyProfilePhoto', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#removemyprofilephoto
     */
    public function removeMyProfilePhoto()
    {
        return $this->endpoint('removeMyProfilePhoto', [], false);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getuserprofileaudios
     */
    public function getUserProfileAudios(array $content)
    {
        return $this->endpoint('getUserProfileAudios', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getusergifts
     */
    public function getUserGifts(array $content)
    {
        return $this->endpoint('getUserGifts', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getchatgifts
     */
    public function getChatGifts(array $content)
    {
        return $this->endpoint('getChatGifts', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#repoststory
     */
    public function repostStory(array $content)
    {
        return $this->endpoint('repostStory', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#approvesuggestedpost
     */
    public function approveSuggestedPost(array $content)
    {
        return $this->endpoint('approveSuggestedPost', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#declinesuggestedpost
     */
    public function declineSuggestedPost(array $content)
    {
        return $this->endpoint('declineSuggestedPost', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#answershippingquery
     */
    public function answerWebAppQuery(array $content)
    {
        return $this->endpoint('answerWebAppQuery', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#answerinlinequery
     */
    public function answerInlineQuery(array $content)
    {
        return $this->endpoint('answerInlineQuery', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setmycommands
     */
    public function setMyCommands(array $content)
    {
        return $this->endpoint('setMyCommands', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#deletemycommands
     */
    public function deleteMyCommands(array $content)
    {
        return $this->endpoint('deleteMyCommands', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getmycommands
     */
    public function getMyCommands(array $content = [])
    {
        return $this->endpoint('getMyCommands', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setmyname
     */
    public function setMyName(array $content)
    {
        return $this->endpoint('setMyName', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getmyname
     */
    public function getMyName(array $content = [])
    {
        return $this->endpoint('getMyName', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setmydescription
     */
    public function setMyDescription(array $content)
    {
        return $this->endpoint('setMyDescription', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getmydescription
     */
    public function getMyDescription(array $content = [])
    {
        return $this->endpoint('getMyDescription', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setmyshortdescription
     */
    public function setMyShortDescription(array $content)
    {
        return $this->endpoint('setMyShortDescription', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getmyshortdescription
     */
    public function getMyShortDescription(array $content = [])
    {
        return $this->endpoint('getMyShortDescription', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setchatmenubutton
     */
    public function setChatMenuButton(array $content = [])
    {
        return $this->endpoint('setChatMenuButton', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getchatmenubutton
     */
    public function getChatMenuButton(array $content = [])
    {
        return $this->endpoint('getChatMenuButton', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setmydefaultadministratorrights
     */
    public function setMyDefaultAdministratorRights(array $content = [])
    {
        return $this->endpoint('setMyDefaultAdministratorRights', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getmydefaultadministratorrights
     */
    public function getMyDefaultAdministratorRights(array $content = [])
    {
        return $this->endpoint('getMyDefaultAdministratorRights', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#editmessagetext
     */
    public function editMessageText(array $content)
    {
        return $this->endpoint('editMessageText', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#editmessagecaption
     */
    public function editMessageCaption(array $content)
    {
        return $this->endpoint('editMessageCaption', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#editmessagemedia
     */
    public function editMessageMedia(array $content)
    {
        return $this->endpoint('editMessageMedia', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#editmessagereplymarkup
     */
    public function editMessageReplyMarkup(array $content)
    {
        return $this->endpoint('editMessageReplyMarkup', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#stoppoll
     */
    public function stopPoll(array $content)
    {
        return $this->endpoint('stopPoll', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#deletemessage
     */
    public function deleteMessage(array $content)
    {
        return $this->endpoint('deleteMessage', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#deletemessages
     */
    public function deleteMessages(array $content)
    {
        return $this->endpoint('deleteMessages', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#copymessage
     */
    public function copyMessage(array $content)
    {
        return $this->endpoint('copyMessage', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#copymessages
     */
    public function copyMessages(array $content)
    {
        return $this->endpoint('copyMessages', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#forwardmessages
     */
    public function forwardMessages(array $content)
    {
        return $this->endpoint('forwardMessages', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendsticker
     */
    public function sendSticker(array $content)
    {
        return $this->endpoint('sendSticker', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getstickerset
     */
    public function getStickerSet(array $content)
    {
        return $this->endpoint('getStickerSet', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getcustomemojistickers
     */
    public function getCustomEmojiStickers(array $content)
    {
        return $this->endpoint('getCustomEmojiStickers', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#uploadstickerfile
     */
    public function uploadStickerFile(array $content)
    {
        return $this->endpoint('uploadStickerFile', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#createnewstickerset
     */
    public function createNewStickerSet(array $content)
    {
        return $this->endpoint('createNewStickerSet', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#addstickertoset
     */
    public function addStickerToSet(array $content)
    {
        return $this->endpoint('addStickerToSet', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setstickerpositioninset
     */
    public function setStickerPositionInSet(array $content)
    {
        return $this->endpoint('setStickerPositionInSet', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#deletestickerfromset
     */
    public function deleteStickerFromSet(array $content)
    {
        return $this->endpoint('deleteStickerFromSet', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setstickeremojilist
     */
    public function setStickerEmojiList(array $content)
    {
        return $this->endpoint('setStickerEmojiList', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setstickerkewords
     */
    public function setStickerKeywords(array $content)
    {
        return $this->endpoint('setStickerKeywords', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setstickermaskposition
     */
    public function setStickerMaskPosition(array $content)
    {
        return $this->endpoint('setStickerMaskPosition', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setstickersettitle
     */
    public function setStickerSetTitle(array $content)
    {
        return $this->endpoint('setStickerSetTitle', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setstickersetthumbnail
     */
    public function setStickerSetThumbnail(array $content)
    {
        return $this->endpoint('setStickerSetThumbnail', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setstickersetthumbnail
     */
    public function setStickerSetThumb(array $content) // Alias
    {
        return $this->setStickerSetThumbnail($content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#sendgame
     */
    public function sendGame(array $content)
    {
        return $this->endpoint('sendGame', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setgamescore
     */
    public function setGameScore(array $content)
    {
        return $this->endpoint('setGameScore', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getgamehighscores
     */
    public function getGameHighScores(array $content)
    {
        return $this->endpoint('getGameHighScores', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getupdates
     */
    public function getUpdates(array $content = [])
    {
        $this->updates = $this->endpoint('getUpdates', $content);
        if (is_array($this->updates) && isset($this->updates['result'])) {
            return $this->updates['result'];
        }
        return [];
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#setwebhook
     */
    public function setWebhook(array $content)
    {
        return $this->endpoint('setWebhook', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#deletewebhook
     */
    public function deleteWebhook(array $content = [])
    {
        return $this->endpoint('deleteWebhook', $content);
    }

    /**
     * @param array $content
     * @return array|null
     * @see https://core.telegram.org/bots/api#getwebhookinfo
     */
    public function getWebhookInfo(array $content = [])
    {
        return $this->endpoint('getWebhookInfo', $content, false);
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (empty($this->data)) {
            $rawData = file_get_contents('php://input');
            return json_decode($rawData, true) ?? [];
        }
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getUpdatesFromWebhook()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function inlineKeyboard($inlineKeyboard = [])
    {
        return json_encode(['inline_keyboard' => $inlineKeyboard]);
    }

    /**
     * @return string
     */
    public function replyKeyboardMarkup($keyboard = [], $resize = false, $oneTime = false, $selective = false, $persistent = false, $inputFieldPlaceholder = null, $style = null, $isPaid = null)
    {
        return json_encode([
            'keyboard' => $keyboard,
            'resize_keyboard' => $resize,
            'one_time_keyboard' => $oneTime,
            'selective' => $selective,
            'persistent' => $persistent,
            'input_field_placeholder' => $inputFieldPlaceholder,
            'style' => $style,
            'is_paid' => $isPaid,
        ]);
    }

    /**
     * @return string
     */
    public function replyKeyboardRemove($selective = false)
    {
        return json_encode(['remove_keyboard' => true, 'selective' => $selective]);
    }

    /**
     * @return string
     */
    public function forceReply($selective = false, $inputFieldPlaceholder = null)
    {
        return json_encode(['force_reply' => true, 'selective' => $selective, 'input_field_placeholder' => $inputFieldPlaceholder]);
    }

    /**
     * @param array $inlineKeyboard
     * @param string|null $inputFieldPlaceholder
     * @param bool|null $isPersistent
     * @param int|null $style
     * @param bool|null $isPaid
     * @return string
     * @deprecated Use replyKeyboardMarkup
     */
    public function buildKeyBoard(array $inlineKeyboard = [], $inputFieldPlaceholder = null, $isPersistent = null, $style = null, $isPaid = null)
    {
        return $this->replyKeyboardMarkup($inlineKeyboard, false, false, false, $isPersistent, $inputFieldPlaceholder, $style, $isPaid);
    }

    /**
     * @return string
     */
    public function buildInlineKeyBoard(array $inlineKeyboard = [])
    {
        return $this->inlineKeyboard($inlineKeyboard);
    }

    /**
     * @param string $url
     * @param array $content
     * @param bool $post
     * @return bool|string
     */
    private function sendAPIRequest($url, array $content, $post = true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, $post);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (!empty($this->proxy)) {
            if (isset($this->proxy['url'])) {
                curl_setopt($ch, CURLOPT_PROXY, $this->proxy['url']);
            }
            if (isset($this->proxy['port'])) {
                curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxy['port']);
            }
            if (isset($this->proxy['type'])) {
                curl_setopt($ch, CURLOPT_PROXYTYPE, $this->proxy['type']);
            }
            if (isset($this->proxy['auth'])) {
                curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy['auth']);
            }
        }

        $result = curl_exec($ch);
        if ($result === false) {
            $error = curl_error($ch);
            if ($this->log_errors && class_exists('TelegramErrorLogger')) {
                TelegramErrorLogger::log($url, $content, $error);
            }
        }
        curl_close($ch);
        return $result;
    }

    /**
     * @return array
     */
    public function __call($name, $arguments)
    {
        if (method_exists($this, $name)) {
            return $this->$name(...$arguments);
        }
        if (isset($arguments[0]) && is_array($arguments[0])) {
            return $this->endpoint($name, $arguments[0]);
        }
        return $this->endpoint($name, [], false);
    }
}
