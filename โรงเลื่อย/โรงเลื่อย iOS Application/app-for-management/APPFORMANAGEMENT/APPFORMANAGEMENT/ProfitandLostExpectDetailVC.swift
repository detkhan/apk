//
//  ProfitandLostExpectDetailVC.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 3/3/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit
import SwiftyJSON
import Alamofire
import KRProgressHUD
class ProfitandLostExpectDetailVC: UIViewController {
    
    @IBOutlet weak var dateLabel:UILabel!
    @IBOutlet weak var dropdownImage:UIImageView!
   
    @IBOutlet weak var bottomview:UIView!
    @IBOutlet weak var sawmillview:UIView!
    //date picker view
    @IBOutlet weak var datepickerUIView:UIView!
    @IBOutlet weak var datePicker:MonthYearPickerView!
    @IBOutlet weak var collectionview:UICollectionView!
    
    var profitlossObj:[JSON] = []
    var profitObj = [[String]]()
    
    var getSawId:String = ""
    var getMonth:String = ""
    var getYears:String = ""
    
    var getId:NSString = "" as NSString
    var buttonarray = [UIButton]()
    var topicData = ["วันที่", "รายได้รวม", "ค่าใช้จ่ายผลิตรวม", "กำไรขั้นต้น", "ค่าใช้จ่ายคงที่", "กำไร(ขาดทุน)สุทธิ"]
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
        setStringAndLayout()
        getCurrentDate()
        setUICollectionView()
        setButtonFromSawId()
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        profitObj.removeAll()
        profitlossObj.removeAll()
        dismissview()
        buttonarray.removeAll()
    }
    
    func setNavBar(){
        let newToDo = ["TITLE_TEXT":"ประมาณการณ์กำไรขาดทุน",
                       "LEFT_BUTTON":"onClickedToBack",
                       "IMAGE_NAME":"back_icon"]
        TabNavigationView.addNavigationView(dict: newToDo as [String : String], viewController: self ,buttonHidden: false)
    }
    
    func setStringAndLayout(){
        //set date picker view to hidden and show when press button
        datepickerUIView.isHidden = true
        
        //set dropdown image
        dropdownImage.image = UIImage(named: "dropdown")
    }
    
    func getCurrentDate(){
        let date = Date()
        let formatter = DateFormatter()
        formatter.dateFormat = "MM/YYYY"
        let currentDate = formatter.string(from: date)
        dateLabel.text = currentDate
        
    }
    
    func setUICollectionView(){
        let screenSize = UIScreen.main.bounds as CGRect
        let screenWidth = screenSize.width as CGFloat
        let layout: UICollectionViewFlowLayout = UICollectionViewFlowLayout()
        layout.sectionInset = UIEdgeInsetsMake(0, 0, 0, 0);
        layout.itemSize = CGSize(width: screenWidth/6, height: screenWidth/6)
        layout.minimumInteritemSpacing = 0
        layout.minimumLineSpacing = 0
        collectionview!.collectionViewLayout = layout
    }
    
    func setButtonFromSawId(){
        let date = Date()
        let monthformatter = DateFormatter()
        let yearformatter = DateFormatter()
        monthformatter.dateFormat = "MM"
        yearformatter.dateFormat = "YYYY"
        getSawId = String(format: "%@",getId) //get saw id when choose from incomeandoutcome screen
        checkProfitLoss(month: monthformatter.string(from: date), years: yearformatter.string(from: date))
    }
}
//MARK : - show or hide loading screen
extension ProfitandLostExpectDetailVC {
    func showHUD(){
        KRProgressHUD.show(progressHUDStyle: .white, message: "Loading...")
    }
    func hideHUD(){
        DispatchQueue.main.async {
            KRProgressHUD.dismiss()
        }
    }
}

//MARK : - Actions
extension ProfitandLostExpectDetailVC{
    @IBAction func onClickedToBack(){
        _ = navigationController?.popViewController(animated: true)
    }
}
//MARK : - create line and button and get data from service
extension ProfitandLostExpectDetailVC{
    
    func createLabelAndButtom(parameter:[checkProfitloss]){
        buttonarray.removeAll()
        if parameter.count > 0 {
            bottomview.isHidden = false
            var buttonX:CGFloat = 0
            var viewX:CGFloat = 0
            
            var button = UIButton()
            for param in parameter {
                if UIDevice.current.userInterfaceIdiom == .phone {
                    button = UIButton.init(frame: CGRect.init(x: buttonX, y: 0, width: (bottomview.frame.size.width / CGFloat(parameter.count) ), height: 50))
                }else{
                    button = UIButton.init(frame: CGRect.init(x: buttonX, y: 0, width: (bottomview.frame.size.width / CGFloat(parameter.count) ), height: 70))
                }
                let view = UIView.init(frame: CGRect.init(x: viewX, y: 5, width: 1, height: bottomview.frame.size.height-10))
                buttonX += bottomview.frame.size.width / CGFloat(parameter.count)
                viewX += bottomview.frame.size.width / CGFloat(parameter.count)
                view.backgroundColor = UIColor.lightGray
                button.setTitleColor(UIColor.lightGray, for: UIControlState.normal)
                button.setTitle(param.shortname, for: UIControlState.normal)
                button.tag = Int(param.sawId)!
                button.addTarget(self, action: #selector(onClickedChangeSawmill(sender:)), for: UIControlEvents.touchUpInside)
                buttonarray.append(button)
                sawmillview.addSubview(view)
                sawmillview.addSubview(button)
            }
            if getSawId != ""{
                for bt in buttonarray {
                    if bt.tag == Int(getSawId) {
                        bt.setTitleColor(UIColor.init(colorLiteralRed: 0, green: 122/255, blue: 255/255, alpha: 1), for: .normal)
                    }else{
                        bt.setTitleColor(UIColor.lightGray, for: .normal)
                    }
                }
            }else{
                getSawId = parameter[0].sawId
                for bt in buttonarray {
                    if bt.tag == Int(getSawId) {
                        bt.setTitleColor(UIColor.init(colorLiteralRed: 0, green: 122/255, blue: 255/255, alpha: 1), for: .normal)
                    }else{
                        bt.setTitleColor(UIColor.lightGray, for: .normal)
                    }
                }
            }
        }
        if getSawId != "" {
            let date = Date()
            let monthformatter = DateFormatter()
            let yearformatter = DateFormatter()
            monthformatter.dateFormat = "MM"
            yearformatter.dateFormat = "YYYY"
            let month : NSString = monthformatter.string(from: date) as NSString
            let year : NSString = yearformatter.string(from: date) as NSString
            if month.isEqual(to: getMonth) && year.isEqual(to: getYears){
                getProfitloss(sawId: getSawId, month: monthformatter.string(from: date), years: yearformatter.string(from: date))
            }else{
                if getMonth.isEmpty && getYears.isEmpty {
                    getProfitloss(sawId: getSawId, month: monthformatter.string(from: date), years: yearformatter.string(from: date))
                }else{
                    getProfitloss(sawId: getSawId, month: getMonth, years: getYears)
                }
            }
        }
        getSawId = ""
    }
    
    func onClickedChangeSawmill(sender:UIButton){
        let date = Date()
        let monthformatter = DateFormatter()
        let yearformatter = DateFormatter()
        monthformatter.dateFormat = "MM"
        yearformatter.dateFormat = "YYYY"
        let month : NSString = monthformatter.string(from: date) as NSString
        let year : NSString = yearformatter.string(from: date) as NSString
        if month.isEqual(to: getMonth) && year.isEqual(to: getYears){
            getProfitloss(sawId: String(format:"%d",sender.tag), month: monthformatter.string(from: date), years: yearformatter.string(from: date))
        }else{
            if getMonth.isEmpty && getYears.isEmpty {
                getProfitloss(sawId: String(format:"%d",sender.tag), month: monthformatter.string(from: date), years: yearformatter.string(from: date))
            }else{
                getProfitloss(sawId: String(format:"%d",sender.tag), month: getMonth, years: getYears)
            }
        }
        //change button title in buttonarray
        for bt in buttonarray {
            if bt.tag == sender.tag {
                bt.setTitleColor(UIColor.init(colorLiteralRed: 0, green: 122/255, blue: 255/255, alpha: 1), for: .normal)
            }else{
                bt.setTitleColor(UIColor.lightGray, for: .normal)
            }
        }

    }
    
    func checkProfitLoss(month:String,years:String){
        showHUD()
        let param = ["email" : SetttingController.shareInstance.getEmail(), "dateMonth" : month , "years" : years]
        DispatchQueue.main.async {
            APKController.shareInstance.profitlossdelegate = self;
        }
        APKController.shareInstance.checkProfitloss(parameter: param, Key: ServiceKey.CHECK_PROFIT_LOSS.rawValue)
    }
    
    func getProfitloss(sawId:String,month:String,years:String){
        showHUD()
        DispatchQueue.main.async {
            APKController.shareInstance.profitlossdelegate = self;
        }
        APKController.shareInstance.ProfitLossDetail(parameter: ["sawId" : sawId , "dateMonth" : month, "years" : years ], Key: ServiceKey.PROFIT_LOSS_DETAIL.rawValue)
    }
    
    func dismissview(){
        sawmillview.subviews.forEach({$0.removeFromSuperview()})
    }
}

//MARK : - Actions (when click on date for show datepicker view and select date on date picker view)
extension ProfitandLostExpectDetailVC{
    @IBAction func onClikcedToDatePickerView(){
        if datepickerUIView.isHidden == true {
            datepickerUIView.isHidden = false
        }else{
            datepickerUIView.isHidden = true
        }
    }
    
    @IBAction func onClikcedToSelectDatePickerView(){
        dateLabel.text = String(format:"%02d/%d",datePicker.month,datePicker.year)
        getMonth = String(format: "%02d",datePicker.month)
        getYears = String(format: "%d",datePicker.year)
        checkProfitLoss(month: getMonth, years: getYears)
        if getSawId != "" {
            self.getProfitloss(sawId: getSawId, month: getMonth, years: getYears)
        }
        buttonarray.removeAll()
        datepickerUIView.isHidden = true
        
    }
}

//MARK : - UICollectionViewDataSource , UICollectionViewDelegate
extension ProfitandLostExpectDetailVC : UICollectionViewDelegate , UICollectionViewDataSource{
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        return profitObj[section].count
    }
    
    func numberOfSections(in collectionView: UICollectionView) -> Int {
        return profitObj.count
    }
    
    // make a cell for each cell index path
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
        
        let cell = collectionView.dequeueReusableCell(withReuseIdentifier: "ProfitLossDetailCell", for: indexPath) as! ProfitLossDetailCell
        
        if indexPath.row == 0 {
            if indexPath.section == 0 {
                cell.contentView.backgroundColor = UIColor.init(red: 111/255, green: 206/255, blue: 251/255, alpha: 1)
            }else {
                cell.contentView.backgroundColor = UIColor.init(red: 117/255, green: 255/255, blue: 253/255, alpha: 1)
            }
        }else if indexPath.row == 1 {
            if indexPath.section == 0 {
                cell.contentView.backgroundColor = UIColor.init(red: 252/255, green: 201/255, blue: 66/255, alpha: 1)
            }else{
                cell.contentView.backgroundColor = UIColor.init(red: 255/255, green: 253/255, blue: 208/255, alpha: 1)
            }
        }else if indexPath.row == 2 {
            if indexPath.section == 0 {
                cell.contentView.backgroundColor = UIColor.init(red: 253/255, green: 203/255, blue: 159/255, alpha: 1)
            }else{
                cell.contentView.backgroundColor = UIColor.init(red: 254/255, green: 217/255, blue: 198/255, alpha: 1)
            }
        }else if indexPath.row == 3 {
            if indexPath.section == 0 {
                cell.contentView.backgroundColor = UIColor.init(red: 204/255, green: 204/255, blue: 204/255, alpha: 1)
            }else{
                cell.contentView.backgroundColor = UIColor.init(red: 243/255, green: 243/255, blue: 243/255, alpha: 1)
            }
        }else if indexPath.row == 4 {
            if indexPath.section == 0 {
                cell.contentView.backgroundColor = UIColor.init(red: 102/255, green: 102/255, blue: 102/255, alpha: 1)
            }else{
                cell.contentView.backgroundColor = UIColor.init(red: 217/255, green: 217/255, blue: 217/255, alpha: 1)
            }
        }else if indexPath.row == 5 {
            if indexPath.section == 0 {
                cell.contentView.backgroundColor = UIColor.init(red: 217/255, green: 217/255, blue: 217/255, alpha: 1)
            }else{
                cell.contentView.backgroundColor = UIColor.init(red: 255/255, green: 255/255, blue: 255/255, alpha: 1)
            }
        }
        
        cell.titleLabel.text = profitObj[indexPath.section][indexPath.item]
        cell.layer.borderWidth = 0.5
        cell.layer.borderColor = UIColor.lightGray.cgColor
        
        
        
        return cell
    }
    
    func collectionView(_ collectionView: UICollectionView, didSelectItemAt indexPath: IndexPath) {
        //print("You selected cell row\(indexPath.item) section\(indexPath.section)")
    }
}

struct checkProfitloss {
    let sawId : String
    let shortname : String
}

extension ProfitandLostExpectDetailVC : ProfitLossDelegate {
    
    func incomingoutcomingRes(response: DataResponse<Any>) {
        //@overide don't do anything
    }

    func profitlossdetailRes(response: DataResponse<Any>) {
        hideHUD()
        profitObj.removeAll()
        if let value = response.result.value {
            let json = JSON.init(value)
            let status : NSString = json[0][0]["data"]["status"].stringValue as NSString
            if status.isEqual(to: "true"){
                let ConcurrentQueue = DispatchQueue(label: "com.appformanagement.jsondownload", qos: .utility)
                ConcurrentQueue.sync {
                    let profitArray = json[1].arrayValue
                    for profit in profitArray {
                        let outcoming = profit["outcoming"].stringValue
                        let grossProfit = profit["grossProfit"].stringValue
                        let costs = profit["costs"].stringValue
                        let incoming = profit["incoming"].stringValue
                        let netProfit = profit["netProfit"].stringValue
                        let datetime = profit["datetime"].stringValue
                        profitObj.append([datetime,incoming,outcoming,grossProfit,costs,netProfit])
                    }
                }
                DispatchQueue.main.async{
                    self.profitObj.insert(self.topicData, at: 0)
                    self.collectionview.reloadData()
                }
            }else{
                profitObj.removeAll()
                self.collectionview.reloadData()
                SetttingController.shareInstance.alertcontroller(viewcontroller: self, title: "Information", message: json[0]["data"]["message"].stringValue, titlesubmit: "OK")
            }
        }
    }
    
    func checkprofitlossRes(response: DataResponse<Any>) {
        dismissview()
        hideHUD()
        var parameters = [checkProfitloss]()
        if let value = response.result.value {
            let json = JSON.init(value)
            let status : NSString = json[0][0]["data"]["status"].stringValue as NSString
            if status.isEqual(to: "true"){
                let ConcurrentQueue = DispatchQueue(label: "com.appformanagement.jsondownload", qos: .utility)
                ConcurrentQueue.sync {
                    let woodpiecesArray = json[1].arrayValue
                    for woodpieces in woodpiecesArray {
                        let sawId = woodpieces["sawId"].stringValue
                        let name = woodpieces["shortname"].stringValue
                        let param = checkProfitloss(sawId:sawId,shortname:name)
                        parameters.append(param)
                    }
                }
                DispatchQueue.main.async {
                    self.createLabelAndButtom(parameter: parameters)
                }
            }else{
                dismissview()
                bottomview.isHidden = true
                profitObj.removeAll()
                self.collectionview.reloadData()
            }
        }
    }
}


