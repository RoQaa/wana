<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JoinAgencyController;
use App\Http\Controllers\AgencyController;
use Twilio\Rest\Client;
use App\Http\Controllers\ChairsController;
use App\Http\Controllers\UserAppController;
use App\Http\Controllers\InboxRoomController;
use App\Http\Controllers\GamesController;
use App\Http\Controllers\FollowRoomController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\VipCenterController;
use App\Http\Controllers\FruitsController;
use App\Http\Controllers\ShopItemController;
use App\Http\Controllers\GuessgameController;
use App\Http\Controllers\MyVipController;
use App\Http\Controllers\VisitorsController;
use App\Http\Controllers\PostLikesController;
use App\Http\Controllers\JoinroomController;
use App\Http\Controllers\PostCommentsController;
use App\Http\Controllers\FreindsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\LuckybagsController;
use App\Http\Controllers\ExchangecoinsController;
use App\Http\Controllers\EmojicategoryController;
use App\Http\Controllers\GiftController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\UserTimeTargetController;
use App\Http\Controllers\GamesLeaderBoardController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ChatroomController;
use App\Http\Controllers\RelationsController;
use App\Http\Controllers\RoomsController;
use App\Http\Controllers\LevelsController;
use App\Http\Controllers\ProfileImagesController;
use App\Http\Controllers\BackgroundController;
use App\Http\Controllers\AchiveModelsController;
use App\Http\Controllers\FamiliesController;
use App\Http\Controllers\UserGiftsController;
use App\Http\Controllers\AdminsController;
use App\Http\Controllers\JoinAgencyRequestController;
 
 use  App\Events\RoomEvent;
use Stevebauman\Location\Facades\Location;
use  App\Events\UserEvent;
use Illuminate\Support\Facades\Http;
use DB as db;
use Storage as ss;
use App\Models\paymentspackages;
use App\Models\UserApp;
use App\Models\payments;
use  App\Events\glopel;
use  App\Events\trinig;
use App\Http\Controllers\Api\ConstDataController;
use App\Http\Controllers\Api\NewGiftController;
use App\Http\Controllers\Api\ShopCategoryCacheController;
use App\Http\Controllers\Api\ShopCategoryController;
use App\Http\Controllers\GiftsTarkingController;
use App\Http\Controllers\RoomGiftsController;
use App\Http\Controllers\GiftCategoryController;
use App\Http\Controllers\EmojiController;
use App\Http\Controllers\ShareRoomController;
use App\Http\Controllers\PostesController;
use App\Http\Controllers\AdminChargeController;
use App\Http\Controllers\RechargesbalanceController;

use App\Http\Controllers\BanedDevicesController;
use App\Http\Controllers\UserModelsController;
use App\Http\Controllers\RoomimagesController;
use App\Http\Controllers\LuckyGiftsTrackController;
use App\Http\Controllers\CountriesController;
use App\Http\Controllers\RoomcategoryController;
use App\Http\Controllers\AgencypaymentsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserMusicController;
use App\Http\Controllers\FamilyAdminsController;
use App\Http\Controllers\AppVersionController;
use App\Http\Controllers\SupervisorsController;
use App\Http\Controllers\BlockListController;
use App\Http\Controllers\PostReportsController;

/*
|--------------------------------------------------------------------------
| API Routes
|-------------------------------- ------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


 Route::group(['middleware' => ['ProtectAgainstSqlInjection'], 'namespace' => 'Api'], function () {
     Route::post('/loginID',[UserAppController::class,'loginID']);

  Route::get('/Roomsimage',[RoomsController::class,'Roomsimage'] );

     Route::post('/UpdateProfile',[UserAppController::class,'UpdateProfile']);

      Route::get('/getuserRoom/{id}/{roomid}',[UserAppController::class,'getuserRoom']);
      Route::get('/UserProfileData/{id}/{userid}',[UserAppController::class,'UserProfileData']);
      Route::get('/SetPublicIp/{id}/{ip}',[UserAppController::class,'SetPublicIp']);
      Route::get('/GetRoomUserFollowing/{id}',[UserAppController::class,'GetRoomUserFollowing']);
      Route::get('/GetPostsUserFollowing/{id}',[UserAppController::class,'GetPostsUserFollowing']);
      Route::get('/Target',[UserAppController::class,'Target']);
      Route::post('/logout',[UserAppController::class,'logout']);
      Route::get('/GetAllFlags',[UserAppController::class,'GetAllFlags']);
      Route::post('/Inviteuser',[UserAppController::class,'Inviteuser']);
      Route::post('/JoinChair',[JoinroomController::class,'JoinChair']);
      Route::post('/LeaveChair',[JoinroomController::class,'LeaveChair']);
      Route::post('/SentLuckyGift',[GiftController::class,'SentLuckyGift']);
      Route::post('/sentGift',[GiftController::class,'sentGift']);
      Route::post('/sendmessage',[MessagesController::class,'sendmessage'] );
      Route::post('/AddChatRoom',[ChatroomController::class,'AddChatRoom'] );
      Route::get('/GetSuporterKarisma/{id}',[GiftsTarkingController::class,'GetSuporterKarisma']);
       Route::post('/UpdateAgecy',[AgencyController::class,'UpdateAgecy']);

      Route::post('/UpdateProfiledashboard',[UserAppController::class,'UpdateProfiledashboard']);

Route::get('/GetMyInboxRoomAdmin/{id}',[InboxRoomController::class,'GetMyInboxRoomAdmin']);

Route::get('/identicatdevice',[UserAppController::class,'identicatdevice'] );

Route::get('/SetHidden/{id}',[UserAppController::class,'SetHidden'] );
Route::post('/userinfo22',[UserAppController::class,'userinfo22']);
Route::post('/SetNewIdbyadmin',[UserAppController::class,'SetNewIdbyadmin']  );

   Route::get('TryUserEvent', function (Request $request) {
     return $request->header('DeviceId');
           event(new UserEvent(22,['coins'=>''],'372'));
           return 'asd';
   });
    Route::post('/GooglePlay',[UserAppController::class,'GooglePlay']);
 
Route::post('/sentGift2',[GiftController::class,'sentGift2']);

 Route::post('/addimage',[UserAppController::class,'addimage']);


Route::get('/sc/{userid}/{coins}',[UserAppController::class,'sc']  );
Route::get('/Addcoinstouser/{userid}/{coins}',[UserAppController::class,'sc']  );
  Route::get('/Getuserprize/{id}',[LevelsController::class,'Getuserprize']);

  Route::post('/SubmitLevelsReward',[LevelsController::class,'SubmitLevelsReward']);

  Route::get('/GetLevels',[LevelsController::class,'GetLevels']);

Route::post('/userinfo',[UserAppController::class,'userinfo']);
  Route::get('/UserProfile/{id}',[RelationsController::class,'UserProfile']);

  Route::get('/UserRelations/{id}',[RelationsController::class,'UserRelations']);
     Route::post('/Resetpassword',[UserAppController::class,'Resetpassword']);
Route::post('/login',[UserAppController::class,'login']);
Route::post('/login2',[UserAppController::class,'login2']);


Route::post('/loginGoogle',[UserAppController::class,'loginGoogle']);
Route::post('/loginGoogle2',[UserAppController::class,'loginGoogle2']);


Route::post('/Verifyaccount',[UserAppController::class,'Verifyaccount']);
Route::post('/Verifyaccount2',[UserAppController::class,'Verifyaccount2']);





   Route::get('/GetConstData',[BannerController::class,'GetConstData'] );

   Route::post('/GetConstData2',[BannerController::class,'GetConstData2'] );


  Route::get('/GetFixedRoom',[RoomsController::class,'GetFixedRoom'] );

Route::post('/SendRelation',[RelationsController::class,'SendRelation']);

Route::post('/AcceptRelation',[RelationsController::class,'AcceptRelation']);

Route::post('/RemoveRelation',[RelationsController::class,'RemoveRelation']);

Route::post('/LeaveRelation',[RelationsController::class,'LeaveRelation']);


Route::post('/JoinRoom',[JoinroomController::class,'JoinRoom']);

        Route::get('/getuserTime/{id}',[UserTimeTargetController::class,'getuserTime'] );

        Route::get('/recordusrttime',[UserTimeTargetController::class,'recordusrttime'] );

        Route::get('/EndSet',[UserTimeTargetController::class,'EndSet']);


        Route::get('/GamesLeaderBoard',[GamesLeaderBoardController::class,'GamesLeaderBoard']);
 Route::get('/FruitContent',[FruitsController::class,'FruitContent']);
Route::post('/PLayFruit',[FruitsController::class,'PLayFruit']);





        Route::get('/ResetToken',[FruitsController::class,'ResetToken']);

           Route::get('/CloseAgency/{id}',[AgencyController::class,'CloseAgency'] );


      Route::get('/EditFamilyyName/{id}/{name}',[FamiliesController::class,'EditFamilyyName'] );

     Route::get('/EditAgencyName/{id}/{name}',[AgencyController::class,'EditAgencyName']);

    Route::get('/totalsum/{id}',[AgencyController::class,'totalsum'] );

   Route::get('/Getmyfollowing/{id}',[FollowController::class,'Getmyfollowing']);

   Route::get('/UpdateAgencyPassword/{id}/{password}',[AgencyController::class,'UpdateAgencyPassword']);

  Route::get('/AgencymembersUpdate',[AgencyController::class,'AgencymembersUpdate']);

 Route::get('/UserAppInput',[ExchangecoinsController::class,'UserAppInput']);

  Route::get('/Getemojicategory',[EmojicategoryController::class,'Getemojicategory']);

Route::get('/getuser',[GamesController::class,'getuser']);

 Route::post('/SendLuckybags',[LuckybagsController::class,'SendLuckybags']);

 Route::post('/AcceptLuckybags',[LuckybagsController::class,'AcceptLuckybags']);

 Route::post('/Exchangecoins',[ExchangecoinsController::class,'Exchangecoins']);


Route::get('/Agencymembers/{id}',[AgencyController::class,'Agencymembers']);
Route::get('/RePriceshopitem',[ShopItemController::class,'RePriceshopitem']);
Route::post('/AcceptGuessgame',[GuessgameController::class,'AcceptGuessgame']);
Route::get('/GetAllGames',[GamesController::class,'GetAllGames']);
 //---------------------------------------------------------------Vip
Route::get('/GetVip',[VipCenterController::class,'GetVip']);
Route::post('/AddVip',[VipCenterController::class,'AddVip']);
Route::post('/ByeVip',[MyVipController::class,'ByeVip']);
Route::post('/RemoveVip',[MyVipController::class,'RemoveVip']);
Route::post('/UseVip',[MyVipController::class,'UseVip']);
Route::get('/GetMyVips/{id}',[MyVipController::class,'GetMyVips']);
    //------------------------------------------------follow
Route::post('/Followuser',[FollowController::class,'Followuser']);
Route::post('/ReturnFollow',[FollowController::class,'ReturnFollow'] );
Route::post('/RemoveFollow',[FollowController::class,'RemoveFollow']);
Route::post('/RemoveUserFollow',[FollowController::class,'RemoveUserFollow']);
Route::get('/Getmyfollowers/{id}',[FollowController::class,'Getmyfollowers']);
Route::get('/Getmyfrinds/{id}',[FollowController::class,'Getmyfrinds']);
Route::post('/RemoveFollowRoom',[FollowController::class,'RemoveFollowRoom']);
 //---------------------------------------------visitors
 Route::get('/Getmyvisitors/{id}',[VisitorsController::class,'Getmyvisitors']);
 //-----------------------------------------------Likes
Route::post('/AddLike',[PostLikesController::class,'AddLike']);
Route::post('/RemoveLike',[PostLikesController::class,'RemoveLike']);
//-------------------------------------------------Comment
Route::post('/AddComment',[PostCommentsController::class,'AddComment']);
Route::post('/ReplayComment',[PostCommentsController::class,'ReplayComment']);
//-------------------------------------------------InBoxRooms
Route::post('/CreateInboxRoom',[InboxRoomController::class,'CreateInboxRoom']);
Route::get('/GetMyInboxRoom/{id}',[InboxRoomController::class,'GetMyInboxRoom']);
Route::get('/GetMyInboxRoomDashboard/{id}',[InboxRoomController::class,'GetMyInboxRoomDashboard']);

Route::post('/ReadInboxRoom',[InboxRoomController::class,'ReadInboxRoom']);
Route::get('/deleteInboxRoom/{id}',[InboxRoomController::class,'deleteInboxRoom']);
Route::get('/deleteInboxRoomandBlockUser/{id}/{Senderid}',[InboxRoomController::class,'deleteInboxRoomandBlockUser']);
 //---------------------------------------------Frindes
Route::post('/sendrequest',[FreindsController::class,'sendrequest']);
Route::post('/acceptfrindrequest',[FreindsController::class,'acceptfrindrequest']);
Route::get('/getuserfrinds/{id}',[FreindsController::class,'getuserfrinds']);
Route::delete('/removefreind/{id}/{frindid}',[FreindsController::class,'removefreind']);
Route::delete('/removerequest/{id}',[FreindsController::class,'removerequest']);
Route::get('/GetMyFriends/{id}',[FreindsController::class,'GetMyFriends']);
Route::get('/CheckFrindstateFriends/{id}/{hisid}',[FreindsController::class,'CheckFrindstateFriends']);
 //-------------------------------------------------Report
Route::post('/SendReport',[ReportController::class,'SendReport']);
//--------------------------------------------------User
Route::post('/xxx',[JoinroomController::class,'xxx']);
Route::post('/AddUserNotification',[NotificationsController::class,'AddUserNotification']);
Route::get('/GetUserNotification',[NotificationsController::class,'GetUserNotification']);
Route::get('/sendWebNotification',[NotificationsController::class,'sendWebNotification']);
Route::get('/notificationallusers/{title}/{body}',[NotificationsController::class,'notificationallusers']);
//----------------------------------------------------------Agency


 Route::get('/GetImportantAgancy',[AgencyController::class,'GetImportantAgancy']  );
Route::get('/GetAgencyInfo/{id}',[AgencyController::class,'GetAgencyInfo']  );

Route::get('/GetAgencyInfoWeb/{id}',[AgencyController::class,'GetAgencyInfoWeb'] );

Route::get('/AgencyLogin/{name}/{password}',[AgencyController::class,'AgencyLogin'] );

Route::get('/CheckAgencyLogin/{id}/{pass}',[AgencyController::class,'CheckAgencyLogin'] );

Route::get('/RemoveUserFromAgency/{id}',[AgencyController::class,'RemoveUserFromAgency'] );



Route::get('/joinuserbyid/{id}',[UserAppController::class,'joinuserbyid'] );
Route::post('/usersocialinformation',[UserAppController::class,'usersocialinformation'] );
Route::get('/userinformation/{id}/{playerid}',[UserAppController::class,'userinformation']);
Route::post('/Socialregister',[UserAppController::class,'Socialregister']);
Route::post('/Setframe',[UserAppController::class,'Setframe']);
Route::post('/SetEntry',[UserAppController::class,'SetEntry']);
Route::post('/Setbubbles',[UserAppController::class,'Setbubbles']);
Route::post('/SetHidden',[UserAppController::class,'SetHidden']);
Route::get('/SearchUser/{tittle}',[UserAppController::class,'SearchUser']);
Route::post('/SetColordmessage',[UserAppController::class,'SetColordmessage']);
Route::post('/removebubbles',[UserAppController::class,'removebubbles']);
Route::post('/removeframe',[UserAppController::class,'removeframe']);
Route::post('/removeEntry',[UserAppController::class,'removeEntry']);

Route::post('/SetEnterbubles',[UserAppController::class,'SetEnterbubles']);

Route::post('/removeEnterbubles',[UserAppController::class,'removeEnterbubles']);
Route::post('/RemoveProfilebubles',[UserAppController::class,'RemoveProfilebubles']);

Route::post('/Setprofilebubles',[UserAppController::class,'Setprofilebubles']);

Route::post('/mygifts',[UserAppController::class,'mygifts']);

Route::post('/SetNewId',[UserAppController::class,'SetNewId']);
Route::post('/SetHidden',[UserAppController::class,'SetHidden']);
Route::post('/AddUserGifts',[UserGiftsController::class,'AddUserGifts']);
Route::post('/AddUserGifts2',[UserGiftsController::class,'AddUserGifts2']);

Route::get('/GetMyUserGifts/{id}',[UserGiftsController::class,'GetMyUserGifts']);


Route::get('/Removeroom/{id}',[UserAppController::class,'Removeroom']);


//---------------------------------------------Posts


Route::post('/Leaveapp',[JoinroomController::class,'Leaveapp']);




  Route::post('/JoinAgency',[JoinAgencyController::class,'JoinAgency']);
  Route::post('/NewGuessgame',[GuessgameController::class,'NewGuessgame']);

  Route::get('/AgencysLeaderBoard', [LeaderboardController::class,'AgencysLeaderBoard']);

  Route::get('/DeleteProfileImage/{id}',[ProfileImagesController::class,'DeleteProfileImage']);


 Route::get('/GetUserHours',[BackgroundController::class,'GetUserHours'] );
 Route::get('/GetUserKarismaDetales/{id}/{Roomid}',[GiftsTarkingController::class,'GetUserKarismaDetales'] );
  Route::get('/GetUserKarismaDetales2/{id}/{Roomid}/{chairid}',[GiftsTarkingController::class,'GetUserKarismaDetales2'] );

 ;
 Route::get('/GetJoinTime/{id}',[AchiveModelsController::class,'GetJoinTime'] );


Route::get('/ReturnExchangecoins',[ExchangecoinsController::class,'ReturnExchangecoins']);
Route::post('/AddUserVip',[MyVipController::class,'AddUserVip'] );
 Route::post('/RefundUser',[GiftsTarkingController::class,'RefundUser']);
 Route::get('/GetAgency',[AgencyController::class,'GetAgency'] );
//  Route::get('/GetShopCategory','ShopCategoryController@GetShopCategory');
 Route::post('/Addshopitem',[ShopItemController::class,'Addshopitem'] );
 Route::get('/GetCategory',[GiftCategoryController::class,'GetCategory'] );
 Route::post('/AddUserModel',[UserModelsController::class,'AddUserModel']);

 Route::post('/AddRoomimages',[RoomimagesController::class,'AddRoomimages']);

Route::get('/Getusersum/{id}',[LuckyGiftsTrackController::class,'Getusersum']);
Route::post('/AddAppidVip',[MyVipController::class,'AddAppidVip'] );

Route::get('/AddCountries',[CountriesController::class,'AddCountries']);

Route::get('/getsumgifts/{id}',[RoomGiftsController::class,'getsumgifts']);

 Route::post('/SendImage',[ChatroomController::class,'SendImage']);

Route::post('/Playdice',[GiftController::class,'Playdice'] );
Route::post('/AddGiftsTarking',[GiftsTarkingController::class,'AddGiftsTarking']);
Route::post('/AddUserMusic',[UserMusicController::class,'AddUserMusic'] );
Route::get('/GetMyMusic/{id}',[UserMusicController::class,'GetMyMusic']);


Route::post('/SetThroneChair',[RoomsController::class,'SetThroneChair']);
Route::post('/ChangeEffictStatus',[RoomsController::class,'ChangeEffictStatus']);

Route::get('/GetRoomLeaderboard', [LeaderboardController::class,'GetRoomLeaderboard']);

Route::get('/GetReciverLeaderboard', [LeaderboardController::class,'GetReciverLeaderboard']);
Route::get('/GetFamilyStar/{id}',[LeaderboardController::class,'GetFamilyStar']);
Route::get('/GetTopStar/{id}',[LeaderboardController::class,'GetTopStar']);


Route::get('/GetGiverLeaderboard',[LeaderboardController::class,'GetGiverLeaderboard']);

Route::get('/GetFamilyLeaderboard',[LeaderboardController::class,'GetFamilyLeaderboard']);

Route::post('/AddLeaderboard',[LeaderboardController::class,'AddLeaderboard']);

Route::get('/christmas',[UserGiftsController::class,'christmas'] );

Route::post('/ChangeChair',[JoinroomController::class,'ChangeChair']);
Route::post('/ReturntoAdminChair',[JoinroomController::class,'ReturntoAdminChair']);
Route::post('/LuckyGiftsTrack',[GiftController::class,'LuckyGiftsTrack']);
Route::post('/AdminChangeChair',[JoinroomController::class,'AdminChangeChair']);
Route::post('/Playrollet',[GiftController::class,'Playrollet']);
Route::post('/UpdateShopItem',[ShopItemController::class,'UpdateShopItem']);


Route::post('/FollowRoom',[FollowRoomController::class,'FollowRoom']);
  Route::get('/Getupdateroombumber',[RoomsController::class,'Getupdateroombumber']);
 Route::post('/RemoveFollowRooms',[FollowRoomController::class,'RemoveFollowRoom']);

 Route::get('/GetFollowRoom/{userid}',[FollowRoomController::class,'GetFollowRoom']);
Route::get('/GetRoomKarismas/{roomid}','RoomKarismaController@GetRoomKarismas');


Route::get('/LuckyGiftssum',[GiftController::class,'LuckyGiftssum']);

Route::post('/AddAdmins',[FamilyAdminsController::class,'AddAdmins']);
Route::post('/RemoveAdmin',[FamilyAdminsController::class,'RemoveAdmin'] );


//Familly
Route::post('/CreateFamily',[FamiliesController::class,'CreateFamily'] );

Route::get('/GetFamilyMembers/{id}',[FamiliesController::class,'GetFamilyMembers'] );

Route::get('/GetAllFamily',[FamiliesController::class,'GetAllFamily'] );

Route::post('/joinFamily',[FamiliesMembersController::class,'joinFamily']);
Route::post('/Acceptjoin',[FamiliesMembersController::class,'Acceptjoin']);

Route::get('/GetRequestFamily/{id}',[FamiliesMembersController::class,'GetRequestFamily']);

Route::post('/LeaveFamily',[FamiliesMembersController::class,'LeaveFamily']);

Route::post('/Canclejoin',[FamiliesMembersController::class,'Canclejoin']);

Route::get('/GetFamilyRooms/{id}',[FamiliesController::class,'GetFamilyRooms']);
Route::get('/location', function (Request $request) {
   // $ip = '162.159.24.227'; /* Static IP address */
    $ip = $request->ip();
    $currentUserInfo = Location::get($ip);

return     $currentUserInfo ;
});

Route::get('/GetPaypalPackage','PaypalPackageController@GetPaypalPackage');
Route::post('/AddPostReport',[PostReportsController::class,'AddPostReport']);
Route::post('/AddBlockList',[BlockListController::class,'AddBlockList'] );
Route::post('/UnBlockUser',[BlockListController::class,'UnBlockUser'] );


  Route::post('/DisbandRoom2',[JoinroomController::class,'DisbandRoom2'] );
Route::post('/Addemoji',[EmojiController::class,'Addemoji']);
///----------------------------------ShareRoom
Route::post('/SentShareRoom',[ShareRoomController::class,'SentShareRoom'] );
Route::post('/SentInviteChair',[ShareRoomController::class,'SentInviteChair']);
Route::get('/GetRoomSupervisors/{id}',[SupervisorsController::class,'GetRoomSupervisors']  );

Route::get('/GetSharefrinds/{id}/{Roomid}',[ShareRoomController::class,'GetSharefrinds']);
///----------------------------------

Route::get('/GetBanner',[BannerController::class,'GetBanner']);

Route::get('/deleteBanner/{id}',[BannerController::class,'deleteBanner']);
Route::get('/deleteBackground/{id}',[BannerController::class,'deleteBackground']);
Route::get('/ChangeBannerState/{id}',[BannerController::class,'ChangeBannerState']);

Route::get('/ChangeBackgroundrState/{id}',[BannerController::class,'ChangeBackgroundrState']);
Route::post('/AddStartBanner','StartBannerController@AddStartBanner');

Route::get('/ChangeStartBannerState/{id}','StartBannerController@ChangeStartBannerState');
Route::post('/Sendemoji',[EmojiController::class,'Sendemoji']);

 Route::post('/AddAgency',[AgencyController::class,'AddAgency']);
Route::get('/FixedRooms',[RoomsController::class,'FixedRooms']);



Route::get('/ExploreRooms',[RoomsController::class,'ExploreRooms']);

Route::post('/CreateRoom',[RoomsController::class,'AddRoom']);
Route::post('/CloseRoom',[RoomsController::class,'CloseRoom']);
 Route::post('/UpdateRoom',[RoomsController::class,'UpdateRoom']);

Route::post('/UpdateRoomWeb',[RoomsController::class,'UpdateRoomWeb']);

Route::post('/SetPasswordRoom',[RoomsController::class,'SetPasswordRoom']);
Route::post('/RemovePasswordRoom',[RoomsController::class,'RemovePasswordRoom']);
Route::get('/GetTrendRooms',[RoomsController::class,'GetTrendRooms']);
Route::get('/SearchRoom/{tittle}',[RoomsController::class,'SearchRoom']);

Route::get('/SearchwebRoom/{tittle}',[RoomsController::class,'SearchwebRoom']);
//---------------------------------------------kickuser

Route::post('/unkickuser','KickedusersController@unkickuser');
Route::get('/getkickeduser/{id}','KickedusersController@getkickeduser');
//---------------------------------------------joinuser

Route::post('/LeaveRoom',[JoinroomController::class,'LeaveRoom'] );

Route::post('/Evictionuser',[JoinroomController::class,'Evictionuser']);
Route::post('/updatemutechair',[JoinroomController::class,'updatemutechair']);
Route::post('/Getjoinusers',[JoinroomController::class,'Getjoinusers']);
Route::post('/Removeadminroom',[JoinroomController::class,'Removeadminroom']);
Route::post('/DeleteRoomChat',[JoinroomController::class,'DeleteRoomChat']);
//---------------------------------------------joinuser

 Route::post('/DisbandRoom',[JoinroomController::class,'DisbandRoom']);
//---------------------------------------------Category_Gigt
Route::post('/AddCategory',[GiftCategoryController::class,'AddCategory']);
Route::post('/RemoveCategory',[GiftCategoryController::class,'RemoveCategory']);
Route::post('/UpdateCategory',[GiftCategoryController::class,'UpdateCategory']);

//---------------------------------------------Gift
Route::post('/AddGift',[GiftController::class,'AddGift']);


Route::post('/JoinAgency',[JoinAgencyController::class,'JoinAgency'] );
Route::get('/GetJoinAgency/{id}/{UserId}',[JoinAgencyController::class,'GetJoinAgency'] );
Route::get('/SearchAgency/{tittle}',[AgencyController::class,'SearchAgency']);
Route::post('/LeaveAgency','AgencyLeaverequestController@LeaveAgency');
Route::post('/RequestJoinAgency',[JoinAgencyRequestController::class,'RequestJoinAgency']);
Route::post('/AddMusicEntry','MusicEntryController@AddMusicEntry');
Route::post('/RemoveGift',[GiftController::class,'RemoveGift']);
Route::post('/UpdateGift',[GiftController::class,'UpdateGift']);
Route::post('/GetRoomGift',[GiftController::class,'GetRoomGift']);
Route::post('/sentGift',[GiftController::class,'sentGift']);
//---------------------------------------------Room_Gift
Route::post('/sendgifts',[RoomGiftsController::class,'sendgifts']);

Route::post('/SentCompo',[GiftController::class,'SentCompo']);


Route::post('/RemoveGift',[RoomGiftsController::class,'RemoveGift']);
Route::post('/UpdateGift',[GiftController::class,'UpdateGift']);
//---------------------------------------------Massage

Route::post('/sendgiftmessage',[MessagesController::class,'sendgiftmessage'] );

Route::post('/sendImage',[MessagesController::class,'sendImage']);
//---------------------------------------------ShopCategory
Route::post('/AddShopCategory',[ShopCategoryController::class,'AddShopCategory']);
Route::post('/RemoveShopCategory',[ShopCategoryController::class,'RemoveShopCategory']);
Route::post('/UpdateShopCategory',[ShopCategoryController::class,'UpdateShopCategory']);
Route::get('/GetuserShopCategory/{id}',[ShopCategoryController::class,'GetuserShopCategory'] );
//---------------------------------------------ShopItem

Route::post('/Removeshopitem',[ShopItemController::class,'Removeshopitem']  );

//---------------------------------------------Sales
Route::post('/byeitem',[SalesController::class,'byeitem']);
Route::post('/SendItem',[SalesController::class,'SendItem']);

Route::get('/validselse/{id}',[SalesController::class,'validselse']);
Route::post('/Getmyitems',[SalesController::class,'Getmyitems']);
//---------------------------------------------shippingpackage
Route::post('/AddShippingPackage',[ShippingPackageController::class,'AddShippingPackage']);
Route::post('/UpdateShippingPackage',[ShippingPackageController::class,'UpdateShippingPackage']);
Route::post('/RemoveShippingPackage',[ShippingPackageController::class,'RemoveShippingPackage']);
//---------------------------------------------shippingcoins
//Route::post('/shippingccount','ShippingController@shippingccount');
//---------------------------------------------JoinAgency
Route::get('/allitem',[ShopItemController::class,'allitem']);
//---------------------------------------------Banner
Route::post('/AddBanner',[BannerController::class,'AddBanner']);

Route::get('/GetPersantage/{cost}',[BannerController::class,'GetPersantage']);

Route::get('/gettoken/{uid}/{token}',[BannerController::class,'gettoken']);



//----------------------------------------------ChatRoom
Route::get('/GetChatRoom',[ChatroomController::class,'GetChatRoom']);





Route::post('/AddMention',[ChatroomController::class,'AddMention']);

Route::get('/Leaveapp/{user_id}/{AppPassword}',[JoinroomController::class,'Leaveapp'] );
Route::get('/CheckPasswordRoom/{id}',[RoomsController::class,'CheckPasswordRoom']);

Route::get('/CheckPasswordRoomnew/{id}',[RoomsController::class,'CheckPasswordRoomnew']);

 Route::get('/CheckPasswordright/{id}/{pass}',[RoomsController::class,'CheckPasswordright']);

Route::post('/Addinsult','InsultController@Addinsult');
//---------------------------------------------Supervisors
Route::post('/AddSupervisors',[SupervisorsController::class,'AddSupervisors'] );
Route::post('/RemoveSupervisors',[SupervisorsController::class,'RemoveSupervisors']  );

//---------------------------------------------Users

Route::post('/userbyToken',[UserAppController::class,'userbyToken'] );

Route::post('/BanedDevices',[BanedDevicesController::class,'BanedDevices']);

Route::post('/addversion',[AppVersionController::class,'addversion'] );
Route::post('/LockChair',[ChairsController::class,'LockChair']);
Route::get('/try',[UserAppController::class,'try'] );

 Route::post('/Updatephoto',[UserAppController::class,'Updatephoto'] );

Route::get('/Rolletcoin/{userid}/{coins}',[UserAppController::class,'Rolletcoin'] );



//---------------------------------------------Rooms


//------------------------------ RoomCategory
Route::post('/AddRoomCategory',[RoomcategoryController::class,'AddRoomCategory']);
//---------------------------  Background
Route::post('/AddBackground',[BackgroundController::class,'AddBackground']);
//-------------------------------------updateuid
Route::post('/updateuid',[UserAppController::class,'updateuid']);


   Route::post('/AddAgency',[AgencyController::class,'AddAgency']);



Route::get('/GetNewRooms',[RoomsController::class,'GetNewRooms']);
Route::get('/GetRooms/{Categoty}',[RoomsController::class,'GetRooms']);
Route::post('/SignUpaccount',[UserAppController::class,'SignUpaccount']);


Route::get('/GetRecomended',[RoomsController::class,'GetRecomended']);
Route::get('/GetRecomended2',[RoomsController::class,'GetRecomended2']);
Route::get('/GetCountries',[CountriesController::class,'GetCountries']);
Route::get('/GetCountryRooms/{city}',[RoomsController::class,'GetCountryRooms']);
//----------------------------------------------
 // Route::post('/AddAgency',[AgencyController::class,'AddAgency']);






Route::post('/AddPost',[PostesController::class,'AddPost']);
Route::get('/GetPosts/{id}',[PostesController::class,'GetPosts']);
Route::get('/GetMyPosts/{id}',[PostesController::class,'GetMyPosts']);
Route::get('/Deletemypost/{id}',[PostesController::class,'Deletemypost']);
















//--------------------------------------------------web
//////////////////////////////////////////////GetRecharges
Route::get('/GetRecharges',[RechargesbalanceController::class,'GetRecharges']);
Route::get('/DelayCharge',[RechargesbalanceController::class,'DelayCharge']);
Route::get('/MonthCharge',[RechargesbalanceController::class,'MonthCharge']);
//////////////////////////////////////////////user
Route::post('/SetNewpasswordbyadmin',[UserAppController::class,'SetNewpasswordbyadmin']);
Route::get('/WebUserProfile/{id}',[UserAppController::class,'WebUserProfile']);

Route::get('/SetDb/{id}',[UserAppController::class,'SetDb']);
Route::get('/SetAnnouncerl/{id}',[UserAppController::class,'SetAnnouncerl']);

Route::get('/SetUserModiator/{id}',[UserAppController::class,'SetUserModiator']);

Route::get('/SetUserAdmin/{id}',[UserAppController::class,'SetUserAdmin']);
Route::get('/SetUserOfficial/{id}',[UserAppController::class,'SetUserOfficial']);
Route::get('/SetUserSupporter/{id}',[UserAppController::class,'SetUserSupporter']);
Route::get('/SetUserSuperAdmin/{id}',[UserAppController::class,'SetUserSuperAdmin']);
Route::get('/SetCustomeService/{id}',[UserAppController::class,'SetCustomeService']);
Route::get('/AllUsersWeb',[UserAppController::class,'AllUsersWeb']);
Route::get('/BanDevice/{id}',[BanedDevicesController::class,'BanDevice']);
Route::get('/BandAccount/{id}',[BanedDevicesController::class,'BandAccount']);


Route::get('/BanDeviceTime/{id}/{time}',[BanedDevicesController::class,'BanDeviceTime']);

Route::get('/RemoveTimeBanned',[BanedDevicesController::class,'RemoveTimeBanned']);


Route::get('/RemoveBanDevice/{id}',[BanedDevicesController::class,'RemoveBanDevice']);

Route::get('/DeleteUserGifts/{id}',[UserGiftsController::class,'DeleteUserGifts']);

Route::get('/GetConstwebData',[BannerController::class,'GetConstwebData']);



Route::post('/ChargeUser',[AdminChargeController::class,'ChargeUser']);

Route::post('/ChargeAgency',[AdminChargeController::class,'ChargeAgency']);

Route::get('/GetAgencyCharges',[AdminChargeController::class,'GetAgencyCharges']);
Route::get('/GetUserCharges',[AdminChargeController::class,'GetUserCharges']);

Route::get('/GetAdmins',[AdminsController::class,'GetAdmins']);

Route::post('/AddAdmins',[AdminsController::class,'AddAdmins']);

Route::get('/ChangeAdminState/{id}',[AdminsController::class,'ChangeAdminState']);

Route::get('/LoginAdmin/{name}/{password}',[AdminsController::class,'LoginAdmin']);

Route::get('/CheckAdminlogin/{id}',[AdminsController::class,'CheckAdminlogin']);

Route::get('/GetJoinRequests/{id}',[JoinAgencyRequestController::class,'GetJoinRequests'] );

Route::get('/AcceptJoinRequests/{id}',[JoinAgencyRequestController::class,'AcceptJoinRequests']);

Route::get('/refuseJoinRequests/{id}',[JoinAgencyRequestController::class,'refuseJoinRequests']);

Route::post('/ChargeAgencyPayments',[AgencypaymentsController::class,'ChargeAgencyPayments']);

Route::get('/GetRoomWeb',[RoomsController::class,'GetRoomWeb']);




Route::get('/GetFamilyProfile/{id}',[FamiliesController::class,'GetFamilyProfile'] );
Route::get('/SearchFAmily/{tittle}',[FamiliesController::class,'SearchFAmily']  );

Route::post('/AddAchiveModels',[AchiveModelsController::class,'AddAchiveModels'] );

Route::get('/GetModels',[AchiveModelsController::class,'GetModels']  );

Route::post('/AddProfileImage',[ProfileImagesController::class,'AddProfileImage']);
Route::get('/ChatWithuser/{id}/{userid}',[InboxRoomController::class,'ChatWithuser']);

Route::get('/GetsupporterWeeklyStar',[LeaderboardController::class,'GetsupporterWeeklyStar']);
        Route::post('sockettyr', function (Request $request) {
                event(new glopel(4,['asd'=>'asd']));


       return 'socketsend';

   });
     
 });

 Route::group(['middleware' => ['AppPassword','UserAuthentication'], 'namespace' => 'Api'], function () {

//user




});

//user


//-----------------------socket---------------//
//joinroom     -- 0
//Leaveroom    -- 2
//joinchair    -- 1
//Leavechair   -- 3
//AddChatRoom  -- 4
//SendGift     -- 5
//Evictionuser -- 6
//DisbandRoom  -- 7
//Updatemute   -- 8
//LockChair    -- 9
//Evictionadminuser -- 10
//updateroom   -- 11
//setpassword  -- 12
//deletechat  -- 13
//AddVisores  -- 14
//removeVisores  -- 15
//---------------------------------------------//


//agency_payments

//php artisan serve --host 0.0.0.0 --port 8000
//php artisan migrate:fresh
//php artisan websocket:ser
//composer remove Vendor/Package guzzlehttp/guzzle
//composer require beyondcode/laravel-websockets
//composer du
//php artisan migrate:refresh --path=database\migrations\2021_09_02_225608_create_orders_table.php
//php artisan make:model   ShopItem -cm
//php artisan make:model   Agency -cm
//php artisan make:middleware AppPassword



///////////////////////////////////////new Api ////////////////////////////////

Route::get('/GetConstData',[ConstDataController::class,'indexAction']);
// Route::get('/get-const-data',[ConstDataController::class,'indexAction']);
Route::get('/delete-const-data',[ConstDataController::class,'deleteData']);



Route::get('/GetShopCategory',[ShopCategoryCacheController::class,'indexAction']);
// Route::get('/get-shop-category',[ShopCategoryCacheController::class,'indexAction']);
Route::get('/delete-shop-category',[ShopCategoryCacheController::class,'deleteData']);


//////////////////////////////?New Gift/////////////////////////////

Route::post('/new-sent-gift',[NewGiftController::class,'NewSentGift']);


Route::get('get-agency-target',[AgencyController::class,'AgencyTargetsCalculate']);



