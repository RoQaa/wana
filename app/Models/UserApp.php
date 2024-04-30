<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
 use DB;
  use Illuminate\Database\Eloquent\Factories\HasFactory;
 use Illuminate\Foundation\Auth\User as Authenticatable;
 use Illuminate\Notifications\Notifiable;
 use Laravel\Sanctum\HasApiTokens;
class UserApp extends Model
{
   use HasApiTokens,  Notifiable;
    protected $table = 'user_apps';

    protected $fillable = [
        'image',
     
        'Enterbubles',
        'name',
        'modiator',
        'entry',
        'year',
        'day',
        'ginder',
        'month',
        'description',
        'Announcer',
        'db',
        'UserIP',
        'ban',
    
        'email',
        'social',
        'social_token',
        'notifi_token',
        'coins',
        'uuid',
        'myappid',
        'bubbles',
        'age',
        'city',
        'frameimage',
        'uid',
        'Level',
        'Karisma',
        'Input',
        'followers',
        'following',
        'giftssent',
        'Flag',
        'friends',
        'AgencyId',
        'AgencyKarisma',
        'ColoredMessage',
        'phone_number',
        'Hidden',
        'Newid',
        'deviceId',
        'FamilyAdmin',
        'FamilyModel',
        'FamilyId',
        'FamilyKarisma',
        'Official',
        'Admin',
        'SuperAdmin',
        'MemberAgency',
        'MoneyAgency',
        'CustomersService','Supporter', 'ginput','SuporrtedImage'

    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
  protected $hidden = ['password','rememper_token','PublicIp'];
    

          public function familyrequest(){
        return $this->hasone(Families_Members::class,"user_id","id")->where('status',0);

    }
         public function family(){
        return $this->hasone(Families::class,"id","FamilyId");

    }
    
    
     public function Agency(){
        return $this->hasone(Agency::class,"id","AgencyId");

    }
    
        public function myroom(){
        return $this->hasone(Rooms::class,"admin_id","id")->where('state',0);

    }
    public function myvip(){
        return $this->hasone(MyVip::class,"user_id","id")->where('status',1);

    }
    public function Allmyvip(){
        return $this->hasmany(MyVip::class,"user_id","id")->where('status',1)->orderBy('created_at', 'DESC');

    }
    public function AllmyPayments(){
        return $this->hasmany(Rechargesbalance::class,"user_id","id")->orderBy('created_at', 'DESC');
    }
    
    public function AllmyUserGifts(){
        return $this->hasmany(UserGifts::class,"user_id","id")->orderBy('created_at', 'DESC');
    }

    public function friendsTo()
    {
        return $this->belongsToMany(User::class, 'freinds', 'user_id', 'sener_id');
         
    }
    public function friendsFrom()
    {
        return $this->belongsToMany(User::class, 'friends', 'sener_id', 'user_id');
           
    }

    public function giftssent(){

        return  $this->hasmany(RoomGifts::class,'user_id','id')->where('state',0);
    }
    public function giftscollect(){

        return  $this->hasmany(RoomGifts::class,'user_id','id')->where('state',1);
    }
 
       public function myjoindroom(){
        return $this->hasone(Joinroom::class,"user_id","id")->where('state',0);
         }

    public function friendships()
    {
        return $this->hasMany(Freinds::class,'sener_id','id');
    }
   public function Models()
    {
        return $this->hasMany(UserModels::class,'user_id','id')->orderBy('created_at', 'DESC') ;
    }
   

    public function Rechargesbalance()
    {
        return  $this->hasMany(Rechargesbalance::class,'user_id','id');
    }
 
    public function ProfileImage()
    {
        return  $this->hasMany(ProfileImages::class,'user_id','id');
    }
  
}

