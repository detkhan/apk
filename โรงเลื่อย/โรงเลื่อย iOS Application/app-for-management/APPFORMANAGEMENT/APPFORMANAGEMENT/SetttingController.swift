//
//  SetttingController.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 1/24/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

//    class var shareInstance : SetttingController{
//        struct Static {
//            static var oneToken : dispatch_time_t = 0
//            static var instance : SetttingController? = nil
//        }
//        dispatch_once(&Static.oneToken) {
//            Static.instance = SetttingController()
//        }
//        return Static.instance!
//    }

import UIKit

private let getandsetemail:String = "getandsetemail"
private let getandsetpassword:String = "getandsetpassword"
private let getandsetfirstname:String = "getandsetfirstname"
private let getandsetlastname:String = "getandsetlastname"
private let getandsetusertype:String = "getandsetusertype"
private let getandsetonoffpasscodelock:String = "getandsetonoffpasscodelock"
private let getandsetpasscode:String = "getandsetpasscode"
class SetttingController: NSObject {
    
    class var shareInstance : SetttingController{
        struct Static{
            static let instance = SetttingController()
        }
        return Static.instance
    }
    
    func setEmail(email:String){
        return UserDefaults.standard.set(email, forKey:getandsetemail)
    }
    func getEmail() -> String{
        return UserDefaults.standard.string(forKey: getandsetemail)!
    }
    func setPassword(password:String){
        return UserDefaults.standard.set(password, forKey: getandsetpassword)
    }
    func getPassword() -> String{
        return UserDefaults.standard.string(forKey: getandsetpassword)!
    }
    func setFirstname(username:String){
        return UserDefaults.standard.set(username, forKey: getandsetfirstname)
    }
    func getFirstname() -> String{
        return UserDefaults.standard.string(forKey: getandsetfirstname)!
    }
    func setLastname(username:String){
        return UserDefaults.standard.set(username, forKey: getandsetlastname)
    }
    func getLastname() -> String{
        return UserDefaults.standard.string(forKey: getandsetlastname)!
    }
    func setUsertype(type:String){
        return UserDefaults.standard.set(type, forKey: getandsetusertype)
    }
    func getUsertype() -> String{
        return UserDefaults.standard.string(forKey: getandsetusertype)!
    }
    func setPasscodeLock(value:Bool){
        return UserDefaults.standard.set(value, forKey: getandsetonoffpasscodelock)
    }
    func getPasscodeLock() -> Bool{
        return UserDefaults.standard.bool(forKey: getandsetonoffpasscodelock)
    }
    func setPasscode(passcode:String){
        return UserDefaults.standard.set(passcode, forKey: getandsetpasscode)
    }
    func getPasscode() -> String{
        return UserDefaults.standard.string(forKey: getandsetpasscode)!
    }
   //MARK: - UIAlertController (UIAlertView) for tell something wrong to ENDUSER
    func alertcontroller(viewcontroller:UIViewController,title:String,message:String,titlesubmit:String){
        let alertController = UIAlertController(title:title,message:message,preferredStyle:UIAlertControllerStyle.alert)
        let submitaction = UIAlertAction(title:titlesubmit,style:UIAlertActionStyle.default)
        alertController.addAction(submitaction)
        viewcontroller.present(alertController, animated: true, completion: nil)
    }
}
