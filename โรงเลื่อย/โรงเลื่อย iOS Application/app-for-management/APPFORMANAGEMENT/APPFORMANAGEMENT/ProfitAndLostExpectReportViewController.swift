//
//  ProfitAndLostExpectReportViewController.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 2/1/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit
import Alamofire
import SwiftyJSON
import KRProgressHUD
class ProfitAndLostExpectReportViewController: UIViewController {
    @IBOutlet weak var tableview:UITableView!
    var inoutcomingObj = [Inoutcoming]()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        setNavBar()
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    override func viewWillAppear(_ animated: Bool) {
        getProfitloss()
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        inoutcomingObj.removeAll()
    }
    
    func setNavBar(){
        let newToDo = ["TITLE_TEXT":"ประมาณการณ์กำไรขาดทุน - รายสาขา",
                       "LEFT_BUTTON":"onClickedToBack",
                       "IMAGE_NAME":"back_icon"]
        TabNavigationView.addNavigationView(dict: newToDo as [String : String], viewController: self , buttonHidden: false)
    }
    
    func getProfitloss(){
        showHUD()
        APKController.shareInstance.profitlossdelegate = self;
        let parameter = ["email" : SetttingController.shareInstance.getEmail()]
        APKController.shareInstance.IncomingOutcoming(parameter: parameter, Key: ServiceKey.INCOMING_OUTCOMING.rawValue)
    }
}
extension ProfitAndLostExpectReportViewController{
    func showHUD(){
        KRProgressHUD.show(progressHUDStyle: .white, message: "Loading...")
    }
    func hideHUD(){
        DispatchQueue.main.async {
            KRProgressHUD.dismiss()
        }
    }
}

//MARK : - Actions back button
extension ProfitAndLostExpectReportViewController {
    @IBAction func onClickedToBack(){
        _ = navigationController?.popViewController(animated: true)
    }
    
    @IBAction func onClikcedToGoAPKDetailVC(){
        let ProfitVC = self.storyboard?.instantiateViewController(withIdentifier: "ProfitandLostExpectDetailVC")
        self.navigationController?.pushViewController(ProfitVC!, animated: true)
    }
}

//MARK : - UITableViewDelegate , UITableViewDataSource
extension ProfitAndLostExpectReportViewController : UITableViewDelegate , UITableViewDataSource {
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return inoutcomingObj.count
    }
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCell(withIdentifier: "ProfitLossCell", for: indexPath) as! ProfitLossCell
        
        let inoutcome = inoutcomingObj[indexPath.row]
        cell.fullname.text = String(format: "โรงเลื่อย สาขา %@",inoutcome.shortname)
        if Double(inoutcome.profitTotal)! > 0{
            cell.profitTotal.text = String(format: "กำไร +%@ บาท",inoutcome.profitTotal)
            cell.profitTotal.textColor = UIColor.green
        }else{
            cell.profitTotal.text = String(format: "ขาดทุน %@ บาท",inoutcome.profitTotal)
            cell.profitTotal.textColor = UIColor.red
        }
        cell.detail.text = "กดเพื่อดูรายงาน"
        
        return cell
    }
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        let inoutcome = inoutcomingObj[indexPath.row]
        let date = Date()
        let monthformatter = DateFormatter()
        let yearformatter = DateFormatter()
        monthformatter.dateFormat = "MM"
        yearformatter.dateFormat = "YYYY"
        showHUD()
        DispatchQueue.main.async {
            APKController.shareInstance.profitlossdelegate = self;
        }
        APKController.shareInstance.ProfitLossDetail(parameter: ["sawId" : inoutcome.sawId , "dateMonth" : monthformatter.string(from: date), "years" : yearformatter.string(from: date) ], Key: ServiceKey.PROFIT_LOSS_DETAIL.rawValue)
    }
}

//MARK : - create model for json
struct Inoutcoming {
    let fullname : String
    let shortname : String
    let profitTotal : String
    let sawId : String
}

//MARK : - ProfitLossDelegate
extension ProfitAndLostExpectReportViewController : ProfitLossDelegate {
    
    func checkprofitlossRes(response: DataResponse<Any>){
        //@overide
    }
    
    func incomingoutcomingRes(response: DataResponse<Any>) {
        hideHUD()
        if let value = response.result.value {
            let json = JSON.init(value)
            let status:NSString = json[0][0]["data"]["status"].stringValue as NSString
            if status.isEqual(to: "true"){
                let ConcurrentQueue = DispatchQueue(label: "com.appformanagement.jsondownload", qos: .utility)
                ConcurrentQueue.sync {
                    let inoutcomingArray = json[1].arrayValue
                    for incoutcomings in inoutcomingArray{
                        let fullname = incoutcomings["fullname"].stringValue
                        let shortname = incoutcomings["name"].stringValue
                        let profitTotal = incoutcomings["profit_total"].stringValue
                        let sawId = incoutcomings["sawId"].stringValue
                        let inoutcome = Inoutcoming(fullname:fullname,shortname:shortname,profitTotal:profitTotal,sawId:sawId)
                        inoutcomingObj.append(inoutcome)
                    }
                }
                DispatchQueue.main.async{
                    self.tableview.reloadData()
                }
            }else{
                SetttingController.shareInstance.alertcontroller(viewcontroller: self, title: "Information", message: json[0]["data"]["message"].stringValue, titlesubmit: "OK")
            }
        }
    }
    
    func profitlossdetailRes(response: DataResponse<Any>) {
        hideHUD()
        if let value = response.result.value {
            let json = JSON.init(value)
            let status : NSString = json[0][0]["data"]["status"].stringValue as NSString
            let sawid : NSString = json[0][0]["data"]["sawId"].stringValue as NSString
            if status.isEqual(to: "true"){
                        let ProfitVC = self.storyboard?.instantiateViewController(withIdentifier: "ProfitandLostExpectDetailVC") as! ProfitandLostExpectDetailVC
                        ProfitVC.profitlossObj = json[1].arrayValue
                        ProfitVC.getId = sawid
                        self.navigationController?.pushViewController(ProfitVC, animated: true)
            }else{
                SetttingController.shareInstance.alertcontroller(viewcontroller: self, title: "Information", message: json[0]["data"]["message"].stringValue, titlesubmit: "OK")
            }
        }
    }
    
}
