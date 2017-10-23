//
//  LoginViewController.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 1/30/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit
import Alamofire
import SwiftyJSON
import KRProgressHUD
class LoginViewController: UIViewController{
    @IBOutlet weak var emailTextField:UITextField!
    @IBOutlet weak var passwordTextFiled:UITextField!
    @IBOutlet weak var logoImage:UIImageView!
    
    private func setDelegate(){
        emailTextField.delegate = self
        passwordTextFiled.delegate = self
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
        logoImage.layer.masksToBounds = false
        logoImage.layer.cornerRadius = logoImage.frame.height/2
        logoImage.clipsToBounds = true
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
// MARK: - Forgetpassword send correct email to backend with api
extension LoginViewController{
    @IBAction func onClickedToForgetpassword(){
        let forgetpasswordVC = self.storyboard?.instantiateViewController(withIdentifier:"ForgetPasswordViewController")
        self.navigationController?.pushViewController(forgetpasswordVC!, animated: true)
    }
}
//MARK : - Login (check username and password in database on server)
extension LoginViewController{
    @IBAction func onClickedLogin(){
        if((emailTextField.text?.characters.count)! > 0 && (passwordTextFiled.text?.characters.count)! > 0){
            if(!(emailTextField.text?.isEmpty)! && !(passwordTextFiled.text?.isEmpty)!){
                let login = ["email" : emailTextField.text,"password" : passwordTextFiled.text]
                DispatchQueue.main.async {
                    APKController.shareInstance.logindelegate = self;
                }
                APKController.shareInstance.userlogin(parameter: login, Key: ServiceKey.LOGIN_USER_RESPONSE.rawValue)
                showHUD()
            }else{
                SetttingController.shareInstance.alertcontroller(viewcontroller: self, title: "Information", message: "Please insert email or password.", titlesubmit: "OK")
            }
        }else{
            SetttingController.shareInstance.alertcontroller(viewcontroller: self, title: "Information", message: "Please insert email or password.", titlesubmit: "OK")
        }
    }
}
//MARK : - UITextFiledDelegate
extension LoginViewController : UITextFieldDelegate {
    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        textField.resignFirstResponder()
        return true
    }
}

extension LoginViewController {
    func showHUD(){
        KRProgressHUD.show(progressHUDStyle: .white, message: "Loading...")
    }
    func hideHUD(){
        DispatchQueue.main.async {
            KRProgressHUD.dismiss()
        }
    }
}

//MARK : - LoginDelegate in APKController
extension LoginViewController : LoginDelegate {
    func loginRes(response: DataResponse<Any>) {
        hideHUD()
        if let value = response.result.value{
            let json = JSON.init(value)
            let status:NSString = json[0]["data"]["status"].stringValue as NSString
            if status.isEqual(to: "true"){
                SetttingController.shareInstance.setEmail(email: json[0]["data"]["email"].stringValue)
                SetttingController.shareInstance.setPassword(password: "")
                SetttingController.shareInstance.setFirstname(username: json[0]["data"]["firstname"].stringValue)
                SetttingController.shareInstance.setLastname(username: json[0]["data"]["lastname"].stringValue)
                SetttingController.shareInstance.setUsertype(type: json[0]["data"]["type"].stringValue)
                let SWRealVC = self.storyboard?.instantiateViewController(withIdentifier: "SWRevealViewController")
                self.navigationController?.pushViewController(SWRealVC!, animated: true)
            }else{
                SetttingController.shareInstance.alertcontroller(viewcontroller: self, title: "Information", message: json[0]["data"]["message"].stringValue, titlesubmit: "OK")
            }
        }
    }
}
