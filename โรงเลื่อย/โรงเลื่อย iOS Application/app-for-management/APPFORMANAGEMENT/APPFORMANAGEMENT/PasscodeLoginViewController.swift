//
//  PasscodeLoginViewController.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 2/6/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit
import SmileLock
class PasscodeLoginViewController: UIViewController {
    
    class var shareInstance : PasscodeLoginViewController{
        struct Static{
            static let instance = PasscodeLoginViewController()
        }
        return Static.instance
    }
    
    @IBOutlet weak var passwordStackView: UIStackView!
    @IBOutlet weak var titlelabel: UILabel!
    @IBOutlet weak var descriptedlabel:UILabel!
    
    //MARK: Property
    var passwordContainerView: PasswordContainerView!
    let kPasswordDigit = 4
    var kPasswordArray:NSMutableArray = []
    
    override func viewDidLoad() {
        super.viewDidLoad()
        //create PasswordContainerView
        passwordContainerView = PasswordContainerView.create(in: passwordStackView, digit: kPasswordDigit)
        passwordContainerView.delegate = self
        
        //customize password UI
        passwordContainerView.tintColor = UIColor.color(.textColor)
        passwordContainerView.highlightedColor = UIColor.color(.blue)
        
    }
    
    @IBAction func onClickedToBack(){
        dismiss(animated: true, completion: nil)
    }
}

extension PasscodeLoginViewController: PasswordInputCompleteProtocol {
    
    func passwordInputComplete(_ passwordContainerView: PasswordContainerView, input: String) {
        if(SetttingController.shareInstance.getPasscodeLock()){
            if validation(input) {
                validationSuccess()
            } else {
                validationFail()
            }
        }
    }
    
    //edit to close button (Edit in lib)
    func touchAuthenticationComplete(_ passwordContainerView: PasswordContainerView, success: Bool, error: NSError?) {
        SetttingController.shareInstance.setPasscodeLock(value: false)
        dismiss(animated: true, completion: nil)
    }
}

private extension PasscodeLoginViewController {
    func validation(_ input: String) -> Bool {
        return input == SetttingController.shareInstance.getPasscode()
    }
    func validationSuccess() {
        dismiss(animated: true, completion: nil)
        
    }
    func validationFail() {
        passwordContainerView.wrongPassword()
    }
}
