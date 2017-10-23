//
//  MenuViewController.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 1/31/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit
import SWRevealViewController
class MenuViewController: UIViewController {

    @IBOutlet weak var avatarImage:UIImageView!
    @IBOutlet weak var name:UILabel!
    @IBOutlet weak var position:UILabel!
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view.
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    override func viewWillAppear(_ animated: Bool) {
        setUserInterface()
    }
    
    func setUserInterface(){
        avatarImage.layer.masksToBounds = false
        avatarImage.layer.cornerRadius = avatarImage.frame.height/2
        avatarImage.clipsToBounds = true
        avatarImage.image = UIImage(named: "profile_icon")
        
        name.text = String(format:"%@ %@",SetttingController.shareInstance.getFirstname(),SetttingController.shareInstance.getLastname())
        position.text = SetttingController.shareInstance.getUsertype()
    }
    
    @IBAction func onClikcedToGoToDialyReport(){
        let SWRealVC = self.storyboard?.instantiateViewController(withIdentifier: "DialyReportViewController")
        self.navigationController?.pushViewController(SWRealVC!, animated: true)
        self.revealViewController().revealToggle(animated: false)
    }
    
    @IBAction func onClikcedToGoToProfitExpectReport(){
        let SWRealVC = self.storyboard?.instantiateViewController(withIdentifier: "ProfitAndLostExpectReportViewController")
        self.navigationController?.pushViewController(SWRealVC!, animated: true)
        self.revealViewController().revealToggle(animated: false)
    }
    
    @IBAction func onClickedToGoToPerformanceReport(){
        let SWRealVC = self.storyboard?.instantiateViewController(withIdentifier: "PerformanceReportViewController")
        self.navigationController?.pushViewController(SWRealVC!, animated: true)
        self.revealViewController().revealToggle(animated: false)
    }
    
    @IBAction func onClickedToGoToSetting(){
        let SWRealVC = self.storyboard?.instantiateViewController(withIdentifier: "SetttingViewController")
        self.navigationController?.pushViewController(SWRealVC!, animated: true)
        self.revealViewController().revealToggle(animated: false)
    }
    
    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destinationViewController.
        // Pass the selected object to the new view controller.
    }
    */

}
