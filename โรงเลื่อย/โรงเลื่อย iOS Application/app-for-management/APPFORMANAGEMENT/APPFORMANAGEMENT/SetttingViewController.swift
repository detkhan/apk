//
//  SetttingViewController.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 1/31/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//
/*
  it's not have keychain when you set passcode because i'm not make keychain to hashmap
   - add keychain on pharse two
 */
import UIKit
import SmileLock
class SetttingViewController: UIViewController {
    
    @IBOutlet weak var passcodelockSwitch:UISwitch!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view.
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    override func viewWillAppear(_ animated: Bool) {
        setNavBar()
        
        let message = SetttingController.shareInstance.getPasscodeLock() as Bool
        passcodelockSwitch.isOn = message
        
        NotificationCenter.default.addObserver(self, selector: #selector(setTurnOffPasscodeLock), name: NSNotification.Name(rawValue: "setTurnOffPasscodeLock"), object: nil)
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        NotificationCenter.default.removeObserver(self, name: NSNotification.Name(rawValue: "setTurnOffPasscodeLock"), object: nil);
    }
    
    func setNavBar(){
        let newToDo = ["TITLE_TEXT":"SETTING",
                       "LEFT_BUTTON":"onClickedToBack",
                       "IMAGE_NAME":"back_icon"]
        TabNavigationView.addNavigationView(dict: newToDo as [String : String], viewController: self ,buttonHidden: false)
    }
    
    @IBAction func onClickedToBack(){
        _ = navigationController?.popViewController(animated: true)
    }
    
    func setTurnOffPasscodeLock(){
        passcodelockSwitch.setOn(false, animated: true)
    }
    
    @IBAction func onoffPassCodeLockScreen(){
        if(passcodelockSwitch.isOn){
            passcodelockSwitch.setOn(true, animated: true)
            setpasscodelockScreen()
        }else{
            UserDefaults.standard.removeObject(forKey: "getandsetpasscode")
            SetttingController.shareInstance.setPasscodeLock(value: false)
            passcodelockSwitch.setOn(false, animated: true)
        }
    }
    
    func setpasscodelockScreen(){
        let loginVC = storyboard?.instantiateViewController(withIdentifier: "SetPasscodeViewController")
        loginVC?.modalTransitionStyle = .crossDissolve
        loginVC?.modalPresentationStyle = .overCurrentContext
        present(loginVC!, animated: true, completion: nil)
    }
}
//MARK - Logout actions
extension SetttingViewController {
    @IBAction func logout(){
        let alertMessage = UIAlertController.init(title: "Information", message: "Are you sure to logout on this account.", preferredStyle: .alert)
        alertMessage.addAction(UIAlertAction.init(title: "Cancel", style: .cancel, handler: nil))
        alertMessage.addAction(UIAlertAction.init(title: "OK", style: .default, handler: { (action:UIAlertAction!) in
            let appDomain = Bundle.main.bundleIdentifier!
            UserDefaults.standard.removePersistentDomain(forName: appDomain)
            let appDelegate = UIApplication.shared.delegate as! AppDelegate
            appDelegate.rootScreen();
        }))
        self.present(alertMessage, animated: true, completion: nil)
    }
}
