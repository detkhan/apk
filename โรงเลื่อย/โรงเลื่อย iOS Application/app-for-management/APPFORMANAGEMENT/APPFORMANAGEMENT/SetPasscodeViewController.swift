//
//  PasswordLoginViewController.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 2/3/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit
import SmileLock
class SetPasscodeViewController: UIViewController {
    
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


extension SetPasscodeViewController: PasswordInputCompleteProtocol {
    
    func passwordInputComplete(_ passwordContainerView: PasswordContainerView, input: String) {
        descriptedlabel.text = "Enter new passcode again"
        passwordContainerView.clearInput()
        kPasswordArray.add(input)
        if(kPasswordArray.count > 1){
            if(NSString(string:input).isEqual(kPasswordArray.object(at: 0))){
                SetttingController.shareInstance.setPasscode(passcode: input)
                SetttingController.shareInstance.setPasscodeLock(value: true)
                dismiss(animated: true, completion: nil)
            }else{
                kPasswordArray.removeAllObjects()
                descriptedlabel.text = "Passcode not match enter new passcode"
                descriptedlabel.textColor = UIColor.red
            }
        }
    }
    
    //edit to close button (Edit in lib)
    func touchAuthenticationComplete(_ passwordContainerView: PasswordContainerView, success: Bool, error: NSError?) {
        SetttingController.shareInstance.setPasscodeLock(value: false)
        NotificationCenter.default.post(name: NSNotification.Name(rawValue: "setTurnOffPasscodeLock"), object: nil)
        dismiss(animated: true, completion: nil)
    }
}

private extension SetPasscodeViewController {
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

