//
//  ForgetPasswordViewController.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 1/30/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit
import Alamofire
import SwiftyJSON
import KRProgressHUD
class ForgetPasswordViewController: UIViewController {
    @IBOutlet weak var logoImage:UIImageView!
    @IBOutlet weak var forgetpasswordtextfiled:UITextField!
    @IBOutlet weak var backImage:UIImageView!
    
    private func setDelegate(){
        forgetpasswordtextfiled.delegate = self
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view.
        setDelegate()
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    override func viewWillAppear(_ animated: Bool) {
        setStringandLayout()
        HideKeyboardWhenTouchAnyWhere()
        if UIDevice.current.userInterfaceIdiom == .phone {
            if UIScreen.main.nativeBounds.height == 960 || UIScreen.main.nativeBounds.height == 1136 {
                NotificationCenter.default.addObserver(self, selector: #selector(keyboardWillShow(notification:)), name: NSNotification.Name.UIKeyboardWillShow, object: nil)
                NotificationCenter.default.addObserver(self, selector: #selector(keyboardWillHide(notification:)), name: NSNotification.Name.UIKeyboardWillHide, object: nil)
            }
        }
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        if UIDevice.current.userInterfaceIdiom == .phone {
            if UIScreen.main.nativeBounds.height == 960 || UIScreen.main.nativeBounds.height == 1136 {
                NotificationCenter.default.removeObserver(self, name: NSNotification.Name.UIKeyboardWillShow, object: nil);
                NotificationCenter.default.removeObserver(self, name: NSNotification.Name.UIKeyboardWillHide, object: nil);
            }
        }
    }
    
    func setStringandLayout(){
        //set corner radius to image (LOGO)
        logoImage.layer.masksToBounds = false
        logoImage.layer.cornerRadius = logoImage.frame.height/2
        logoImage.clipsToBounds = true
        //set back image to UIImageView (Back icon)
        backImage.image = UIImage(named: "back_icon")
    }
    
    func HideKeyboardWhenTouchAnyWhere(){
        let tap: UITapGestureRecognizer = UITapGestureRecognizer(target: self, action: #selector(dismissKeyboard))
        view.addGestureRecognizer(tap)
    }
    
    func dismissKeyboard(){
        view.endEditing(true)
    }
    
    func keyboardWillShow(notification: NSNotification) {
        if let keyboardSize = (notification.userInfo?[UIKeyboardFrameBeginUserInfoKey] as? NSValue)?.cgRectValue {
            if self.view.frame.origin.y == 0{
                self.view.frame.origin.y -= keyboardSize.height
            }
        }
    }
    
    func keyboardWillHide(notification: NSNotification) {
        if let keyboardSize = (notification.userInfo?[UIKeyboardFrameBeginUserInfoKey] as? NSValue)?.cgRectValue {
            if self.view.frame.origin.y != 0{
                self.view.frame.origin.y += keyboardSize.height
            }
        }
    }

}

//MARK : - Actions
extension ForgetPasswordViewController{
    @IBAction func onClickedToBack(){
        _ = navigationController?.popViewController(animated: true)
    }
    
    @IBAction func onClikcedToForgetPassword(){
        if((forgetpasswordtextfiled.text?.characters.count)! > 0){
            let forgetPassword = ["email" : forgetpasswordtextfiled.text]
            DispatchQueue.main.async {
                APKController.shareInstance.forgetpassworddelegate = self;
            }
            APKController.shareInstance.userForgetPassword(parameter: forgetPassword, Key: ServiceKey.FORGET_PASSWORD_RESPONSE.rawValue)
            showHUD()
        }else{
            SetttingController.shareInstance.alertcontroller(viewcontroller: self, title: "Information", message: "Please insert email.", titlesubmit: "OK")
        }
    }
}
//MARK : - show or hide hud
extension ForgetPasswordViewController {
    func showHUD(){
        KRProgressHUD.show(progressHUDStyle: .white, message: "Loading...")
    }
    func hideHUD(){
        DispatchQueue.main.async {
            KRProgressHUD.dismiss()
        }
    }

}

//MARK : - UITextFiledDelegate
extension ForgetPasswordViewController : UITextFieldDelegate {
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        textField.resignFirstResponder()
        return true
    }
}

//MARK : - ForgetpasswordDelegate in APKController
extension ForgetPasswordViewController : ForgetpasswordDelegate {
    func forgetPasswordRes(response: DataResponse<Any>) {
        hideHUD()
        if let value = response.result.value{
            let json = JSON.init(value)
            let status:NSString = json[0]["data"]["status"].stringValue as NSString
            if status.isEqual(to: "true"){
                SetttingController.shareInstance.alertcontroller(viewcontroller: self, title: "Information", message: json[0]["data"]["message"].stringValue, titlesubmit: "OK")
            }else{
                SetttingController.shareInstance.alertcontroller(viewcontroller: self, title: "Information", message: json[0]["data"]["message"].stringValue, titlesubmit: "OK")
            }
        }
    }
}
