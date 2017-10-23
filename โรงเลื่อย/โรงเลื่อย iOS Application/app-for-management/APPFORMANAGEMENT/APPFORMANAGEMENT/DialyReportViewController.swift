//
//  DialyReportViewController.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 1/31/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit
import Alamofire
import SwiftyJSON
import KRProgressHUD
class DialyReportViewController: UIViewController {
    
    @IBOutlet weak var dateLabel:UILabel!
    @IBOutlet weak var dropdownImage:UIImageView!
    //sawmill uiview
    @IBOutlet weak var bottomview:UIView!
    @IBOutlet weak var sawmillview:UIView!
    //date picker view
    @IBOutlet weak var datepickerUIView:UIView!
    @IBOutlet fileprivate var datePicker:MonthYearPickerView!
    @IBOutlet weak var collectionview:UICollectionView! //UICollectionview
    @IBOutlet weak var segmentControl:UISegmentedControl! //segment control

    var truckData = ["วันที่", "ไม้ท่อนเข้า", "ไปเลื่อย", "ไปขาย", "คงเหลือ", "สูญเสีย"]
    var fireWoodData = ["วันที่","เข้า","ไปขาย","คงเหลือ","สูญเสีย"]
    var weightData = ["วันที่","ไม้เกรด (กก.)","ปีกไม้ (กก.)","ขี้เลื่อย (กก.)"]
    
    var data = [[String]]()
    //define for kept data when it's press or something
    var getSawId:String = ""
    var getMonth:String = ""
    var getYears:String = ""
    //array string to kept data from service
    var woodPiecesObj = [[String]]()
    var fireWoodObj = [[String]]()
    var weightOutObj = [[String]]()
    var buttonarray = [UIButton]()
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
        setString()
        getCurrentDate()
        setUICollectionView()
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        ClearAllData()
        dismissview()
        buttonarray.removeAll()
    }
    
    func setNavBar(){
        let newToDo = ["TITLE_TEXT":"รายงานประจำวัน",
                       "LEFT_BUTTON":"onClickedToBack",
                       "IMAGE_NAME":"back_icon"]
        TabNavigationView.addNavigationView(dict: newToDo as [String : String], viewController: self , buttonHidden: false)
    }
    
    func setString(){
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
        
        let date = Date()
        let monthformatter = DateFormatter()
        let yearformatter = DateFormatter()
        monthformatter.dateFormat = "MM"
        yearformatter.dateFormat = "YYYY"
        let month : NSString = monthformatter.string(from: date) as NSString
        let years : NSString = yearformatter.string(from: date) as NSString
        switch segmentControl.selectedSegmentIndex {
        case 0:
            layout.itemSize = CGSize(width: screenWidth/6, height: screenWidth/6)
            if month.isEqual(to: getMonth) && years.isEqual(to: getYears){
                self.checkWoodPieces(month: monthformatter.string(from: date), years: yearformatter.string(from: date))
            }else{
                if getMonth.isEmpty && getYears.isEmpty {
                    self.checkWoodPieces(month: monthformatter.string(from: date), years: yearformatter.string(from: date))
                }else{
                    self.checkWoodPieces(month: getMonth, years: getYears)
                }
            }
            break
            
        case 1:
            layout.itemSize = CGSize(width: screenWidth/5, height: screenWidth/5)
            if month.isEqual(to: getMonth) && years.isEqual(to: getYears){
                self.checkFireWood(month: monthformatter.string(from: date), years: yearformatter.string(from: date))
            }else{
                if getMonth.isEmpty && getYears.isEmpty {
                    self.checkFireWood(month: monthformatter.string(from: date), years: yearformatter.string(from: date))
                }else{
                    self.checkFireWood(month: getMonth, years: getYears)
                }
            }
            break
            
        case 2:
            layout.itemSize = CGSize(width: screenWidth/4, height: screenWidth/4)
            if month.isEqual(to: getMonth) && years.isEqual(to: getYears){
                self.checkWeightOutcoming(month: monthformatter.string(from: date), years: yearformatter.string(from: date))
            }else{
                if getMonth.isEmpty && getYears.isEmpty {
                    self.checkWeightOutcoming(month: monthformatter.string(from: date), years: yearformatter.string(from: date))
                }else{
                    self.checkWeightOutcoming(month: getMonth, years: getYears)
                }
            }
            break
            
        default:
            layout.itemSize = CGSize(width: screenWidth, height: screenWidth)
        }
        layout.minimumInteritemSpacing = 0
        layout.minimumLineSpacing = 0
        DispatchQueue.main.async{
            self.collectionview!.collectionViewLayout = layout
        }
    }
    
    func ClearAllData(){
        woodPiecesObj.removeAll()
        fireWoodObj.removeAll()
        weightOutObj.removeAll()
    }
}

//MARK: - Actions clieck to back
extension DialyReportViewController{
    @IBAction func onClickedToBack(){
        _ = navigationController?.popViewController(animated: true)
    }
}

//MARK : show or hide loading screen
extension DialyReportViewController {
    func showHUD(){
        KRProgressHUD.show(progressHUDStyle: .white, message: "Loading...")
    }
    func hideHUD(){
        DispatchQueue.main.async {
            KRProgressHUD.dismiss()
        }
    }
}

//MARK : post to get sawmill to create button and line
extension DialyReportViewController {
    func checkWoodPieces(month:String , years:String){
        showHUD()
        let param = ["email" : SetttingController.shareInstance.getEmail() , "dateMonth" : month , "years" : years]
        DispatchQueue.main.async {
            APKController.shareInstance.dialydelegate = self;
        }
        APKController.shareInstance.checkwoodpieces(parameter: param, Key: ServiceKey.CHECK_WOOD_PIECES.rawValue)
    }
    
    func checkFireWood(month:String , years:String){
        showHUD()
        let param = ["email" : SetttingController.shareInstance.getEmail() , "dateMonth" : month , "years" : years]
        DispatchQueue.main.async {
            APKController.shareInstance.dialydelegate = self;
        }
        APKController.shareInstance.checkfirewood(parameter: param, Key: ServiceKey.CHECK_FIRE_WOOD.rawValue)
    }
    func checkWeightOutcoming(month:String , years:String){
        showHUD()
        let param = ["email" : SetttingController.shareInstance.getEmail() , "dateMonth" : month , "years" : years]
        DispatchQueue.main.async {
            APKController.shareInstance.dialydelegate = self;
        }
        APKController.shareInstance.checkweightoutcoming(parameter: param, Key: ServiceKey.CHECK_WEIGHT_OUTCOMING.rawValue)
    }
}

//MARK : - Actions (when click on date for show datepicker view and select date on date picker view)
extension DialyReportViewController {
    @IBAction func onClikcedToDatePickerView(){
        if datepickerUIView.isHidden == true {
            datepickerUIView.isHidden = false
        }else{
            datepickerUIView.isHidden = true
        }
    }
    //self.getWoodPiecesFromSawIdMonthYears(sawId: getSawId, month: getMonth, years: getYears)
    //self.getFireWoodFromSawIdMonthYears(sawId: getSawId, month: getMonth, years: getYears)
    //self.getWeightOutFromSawIdMonthYears(sawId: getSawId, month: getMonth, years: getYears)
    @IBAction func onClikcedToSelectDatePickerView(){
        dateLabel.text = String(format:"%02d/%d",datePicker.month,datePicker.year)
        getMonth = String(format:"%02d",datePicker.month)
        getYears = String(format:"%d",datePicker.year)
        switch segmentControl.selectedSegmentIndex {
        case 0:
            self.checkWoodPieces(month: getMonth, years: getYears)
            if getSawId != "" {
                self.getWoodPiecesFromSawIdMonthYears(sawId: getSawId, month: getMonth, years: getYears)
            }
            break
        case 1:
            self.checkFireWood(month: getMonth, years: getYears)
            if getSawId != "" {
                self.getFireWoodFromSawIdMonthYears(sawId: getSawId, month: getMonth, years: getYears)
            }
            break
        case 2:
            self.checkWeightOutcoming(month: getMonth, years: getYears)
            if getSawId != "" {
                self.getWeightOutFromSawIdMonthYears(sawId: getSawId, month: getMonth, years: getYears)
            }
            break
        default:
            break
        }
        ClearAllData()
        buttonarray.removeAll() //remove button array
        datepickerUIView.isHidden = true
    }
}
//MARK : - create button follow sawmill
extension DialyReportViewController {
    func createLabelAndButtom(parameter:[checkSawmill]){
        buttonarray.removeAll() //remove button array
        if parameter.count > 0 {
            bottomview.isHidden = false
            var buttonX:CGFloat = 0
            var viewX:CGFloat = 0
//          if getSawId == "" {
//             if getSawId != parameter[0].sawId {
//                  getSawId = parameter[0].sawId
//             }
//          }
            getSawId = parameter[0].sawId
            var button = UIButton()
            for param in parameter {
                if UIDevice.current.userInterfaceIdiom == .phone {
                    button = UIButton.init(frame: CGRect.init(x: buttonX, y: 0, width: (bottomview.frame.size.width / CGFloat(parameter.count) ), height: 50))
                }else{
                    button = UIButton.init(frame: CGRect.init(x: buttonX, y: 0, width: (bottomview.frame.size.width / CGFloat(parameter.count) ), height: 70))
                }
                //let button = UIButton.init(frame: CGRect.init(x: buttonX, y: 0, width: (bottomview.frame.size.width / CGFloat(parameter.count) ), height: 50))
                let view = UIView.init(frame: CGRect.init(x: viewX, y: 5, width: 1, height: bottomview.frame.size.height-10))
                buttonX += bottomview.frame.size.width / CGFloat(parameter.count)
                viewX += bottomview.frame.size.width / CGFloat(parameter.count)
                view.backgroundColor = UIColor.lightGray
                
                button.setTitle(param.shortname, for: UIControlState.normal)
                button.tag = Int(param.sawId)!
                button.setTitleColor(UIColor.lightGray, for: .normal)
                button.addTarget(self, action: #selector(onClickedChangeSawmill(sender:)), for: UIControlEvents.touchUpInside)
                buttonarray.append(button)
                sawmillview.addSubview(view)
                sawmillview.addSubview(button)
            }
        }
        if getSawId != "" {
            let date = Date()
            let monthformatter = DateFormatter()
            let yearformatter = DateFormatter()
            monthformatter.dateFormat = "MM"
            yearformatter.dateFormat = "YYYY"
            let month : NSString = monthformatter.string(from: date) as NSString
            let years : NSString = yearformatter.string(from: date) as NSString
            switch segmentControl.selectedSegmentIndex {
                
            case 0:
                if month.isEqual(to: getMonth) && years.isEqual(to: getYears){
                    getWoodPiecesFromSawIdMonthYears(sawId: getSawId, month: monthformatter.string(from: date), years: yearformatter.string(from: date))
                }else{
                    if getMonth.isEmpty && getYears.isEmpty {
                        getWoodPiecesFromSawIdMonthYears(sawId: getSawId, month: monthformatter.string(from: date), years: yearformatter.string(from: date))
                    }else{
                        getWoodPiecesFromSawIdMonthYears(sawId: getSawId, month: getMonth, years: getYears)
                    }
                }
                break
                
            case 1:
                if month.isEqual(to: getMonth) && years.isEqual(to: getYears){
                    getFireWoodFromSawIdMonthYears(sawId: getSawId, month: monthformatter.string(from: date), years: yearformatter.string(from: date))
                }else{
                    if getMonth.isEmpty && getYears.isEmpty {
                        getFireWoodFromSawIdMonthYears(sawId: getSawId, month: monthformatter.string(from: date), years: yearformatter.string(from: date))
                    }else{
                        getFireWoodFromSawIdMonthYears(sawId: getSawId, month: getMonth, years: getYears)
                    }
                }
                break
                
            case 2:
                if month.isEqual(to: getMonth) && years.isEqual(to: getYears){
                    getWeightOutFromSawIdMonthYears(sawId: getSawId, month: monthformatter.string(from: date), years: yearformatter.string(from: date))
                }else{
                    if getMonth.isEmpty && getYears.isEmpty {
                        getWeightOutFromSawIdMonthYears(sawId: getSawId, month: monthformatter.string(from: date), years: yearformatter.string(from: date))
                    }else{
                        getWeightOutFromSawIdMonthYears(sawId: getSawId, month: getMonth, years: getYears)
                    }
                }
                break
                
            default:
                break
            }
        }
    }
    
    func onClickedChangeSawmill(sender:UIButton){
        let date = Date()
        let monthformatter = DateFormatter()
        let yearformatter = DateFormatter()
        monthformatter.dateFormat = "MM"
        yearformatter.dateFormat = "YYYY"
        let month : NSString = monthformatter.string(from: date) as NSString
        let years : NSString = yearformatter.string(from: date) as NSString
        switch segmentControl.selectedSegmentIndex {
        case 0:
            if month.isEqual(to: getMonth) && years.isEqual(to: getYears){
                getWoodPiecesFromSawIdMonthYears(sawId: String(format:"%d",sender.tag), month: monthformatter.string(from: date), years: yearformatter.string(from: date))
            }else{
                if getMonth.isEmpty && getYears.isEmpty {
                    getWoodPiecesFromSawIdMonthYears(sawId: String(format:"%d",sender.tag), month: monthformatter.string(from: date), years: yearformatter.string(from: date))
                }else{
                    getWoodPiecesFromSawIdMonthYears(sawId: String(format:"%d",sender.tag), month: getMonth, years: getYears)
                }
            }
            break
            
        case 1:
            if month.isEqual(to: getMonth) && years.isEqual(to: getYears){
                getFireWoodFromSawIdMonthYears(sawId: String(format:"%d",sender.tag), month: monthformatter.string(from: date), years: yearformatter.string(from: date))
            }else{
                if getMonth.isEmpty && getYears.isEmpty {
                    getFireWoodFromSawIdMonthYears(sawId: String(format:"%d",sender.tag), month: monthformatter.string(from: date), years: yearformatter.string(from: date))
                }else{
                    getFireWoodFromSawIdMonthYears(sawId: String(format:"%d",sender.tag), month: getMonth, years: getYears)
                }
            }
            break
            
        case 2:
            if month.isEqual(to: getMonth) && years.isEqual(to: getYears){
                getWeightOutFromSawIdMonthYears(sawId: String(format:"%d",sender.tag), month: monthformatter.string(from: date), years: yearformatter.string(from: date))
            }else{
                if getMonth.isEmpty && getYears.isEmpty {
                    getWeightOutFromSawIdMonthYears(sawId: String(format:"%d",sender.tag), month: monthformatter.string(from: date), years: yearformatter.string(from: date))
                }else{
                    getWeightOutFromSawIdMonthYears(sawId: String(format:"%d",sender.tag), month: getMonth, years: getYears)
                }
            }
            break
            
        default:
            break
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
    
    func getWoodPiecesFromSawIdMonthYears(sawId:String , month:String , years:String){
        let param = ["sawId" : sawId , "dateMonth" : month , "years" : years]
        showHUD()
        DispatchQueue.main.async {
            APKController.shareInstance.dialydelegate = self;
        }
        APKController.shareInstance.woodPieces(parameter: param, Key: ServiceKey.WOOD_PIECES.rawValue)
    }
    
    func getFireWoodFromSawIdMonthYears(sawId:String , month:String , years : String){
        let param = ["sawId" : sawId , "dateMonth" : month , "years" : years]
        showHUD()
        DispatchQueue.main.async {
            APKController.shareInstance.dialydelegate = self;
        }
        APKController.shareInstance.fireWood(parameter: param, Key: ServiceKey.FIRE_WOOD.rawValue)
    }
    
    func getWeightOutFromSawIdMonthYears(sawId:String, month : String , years : String) {
        let param = ["sawId" : sawId , "dateMonth" : month , "years" : years]
        showHUD()
        DispatchQueue.main.async {
            APKController.shareInstance.dialydelegate = self;
        }
        APKController.shareInstance.weightOutcoming(parameter: param, Key: ServiceKey.WEIGHT_OUT_COMING.rawValue)
    }
    
    func dismissview(){
        sawmillview.subviews.forEach({$0.removeFromSuperview()})
    }
}

//MARK : - Actions (change segment control)
extension DialyReportViewController {
    @IBAction func onClikcedToChangeSegmentControl(){
        setUICollectionView()
        collectionview.reloadData()
    }
}

//MARK : - UICollectionViewDataSource , UICollectionViewDelegate
extension DialyReportViewController : UICollectionViewDelegate , UICollectionViewDataSource{
    func collectionView(_ collectionView: UICollectionView, numberOfItemsInSection section: Int) -> Int {
        switch segmentControl.selectedSegmentIndex {
        case 0:
            return woodPiecesObj[section].count
        case 1:
            return fireWoodObj[section].count
        case 2:
            return weightOutObj[section].count
        default:
            return 0
        }
    }
    
    func numberOfSections(in collectionView: UICollectionView) -> Int {
        switch segmentControl.selectedSegmentIndex {
        case 0:
            return woodPiecesObj.count
        case 1:
            return fireWoodObj.count
        case 2:
            return weightOutObj.count
        default:
            return 0
        }
    }
    // make a cell for each cell index path
    func collectionView(_ collectionView: UICollectionView, cellForItemAt indexPath: IndexPath) -> UICollectionViewCell {
    
        let cell = collectionView.dequeueReusableCell(withReuseIdentifier: "DailyReportCell", for: indexPath) as! DailyReportCell
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

        switch segmentControl.selectedSegmentIndex {
        case 0:
            cell.titleLabel.text = woodPiecesObj[indexPath.section][indexPath.item]
        case 1:
            cell.titleLabel.text = fireWoodObj[indexPath.section][indexPath.item]
        case 2:
            cell.titleLabel.text = weightOutObj[indexPath.section][indexPath.item]
        default:
            cell.titleLabel.text = ""
        }
        cell.layer.borderWidth = 0.5
        cell.layer.borderColor = UIColor.lightGray.cgColor
        
        return cell
    }
}

extension DialyReportViewController : UICollectionViewDelegateFlowLayout {
    
}

struct checkSawmill {
    let sawId : String
    let shortname : String
}


//MARK : - DialyDelegate
extension DialyReportViewController : DialyDelegate {
    
    func woodpiecesRes(response: DataResponse<Any>) {
        hideHUD()
        ClearAllData()
        if let value = response.result.value{
            let json = JSON.init(value)
            let status:NSString = json[0][0]["data"]["status"].stringValue as NSString
            if status.isEqual(to: "true"){
                let ConcurrentQueue = DispatchQueue(label: "com.appformanagement.jsondownload", qos: .utility)
                ConcurrentQueue.sync {
                    let woodpiecesArray = json[1].arrayValue
                    for woodpieces in woodpiecesArray {
                        let date = woodpieces["datetime"].stringValue
                        let timbersaw = woodpieces["timber_saw"].stringValue
                        let woodlosts = woodpieces["wood_losts"].stringValue
                        let woodpiecesincome = woodpieces["wood_pieces_incoming"].stringValue
                        let woodsale = woodpieces["wood_sale"].stringValue
                        let woodtotal = woodpieces["wood_total"].stringValue
                        woodPiecesObj.append([date,woodpiecesincome,timbersaw,woodsale,woodtotal,woodlosts])
                    }
                }
                
                DispatchQueue.main.async{
                    self.woodPiecesObj.insert(self.truckData, at: 0)
                    self.collectionview.reloadData()
                }
            }else{
                ClearAllData()
                collectionview.reloadData()
                //SetttingController.shareInstance.alertcontroller(viewcontroller: self, title: "Information", message: json[0]["data"]["message"].stringValue, titlesubmit: "OK")
            }
        }
    }
    
    func firewoodRes(response: DataResponse<Any>) {
        hideHUD()
        ClearAllData()
        if let value = response.result.value {
            let json = JSON.init(value)
            let status : NSString = json[0][0]["data"]["status"].stringValue as NSString
            if status.isEqual(to: "true"){
                let ConcurrentQueue = DispatchQueue(label: "com.appformanagement.jsondownload", qos: .utility)
                ConcurrentQueue.sync {
                    let firewoodArray = json[1].arrayValue
                    for firewoods in firewoodArray {
                        let date = firewoods["datetime"].stringValue
                        let firewoodIncome = firewoods["fire_wood_incoming"].stringValue
                        let firewoodSale = firewoods["fire_wood_sale"].stringValue
                        let firewoodTotal = firewoods["firewood_total"].stringValue
                        let firewoodLosts = firewoods["firewood_losts"].stringValue
                        fireWoodObj.append([date,firewoodIncome,firewoodSale,firewoodTotal,firewoodLosts])
                    }
                }
                DispatchQueue.main.async{
                    self.fireWoodObj.insert(self.fireWoodData, at: 0)
                    self.collectionview.reloadData()
                }
            }else{
                ClearAllData()
                collectionview.reloadData()
                //SetttingController.shareInstance.alertcontroller(viewcontroller: self, title: "Information", message: json[0]["data"]["message"].stringValue, titlesubmit: "OK")
            }
        }
    }
    
    func weightoutcomingRes(response: DataResponse<Any>) {
        hideHUD()
        ClearAllData()
        if let value = response.result.value {
            let json = JSON.init(value)
            let status : NSString = json[0][0]["data"]["status"].stringValue as NSString
            if status.isEqual(to: "true"){
                let ConcurrentQueue = DispatchQueue(label: "com.appformanagement.jsondownload", qos: .utility)
                ConcurrentQueue.sync {
                    let weightoutArray = json[1].arrayValue
                    for weightouts in weightoutArray {
                        let date = weightouts["datetime"].stringValue
                        let woodGrade = weightouts["wood_grades_weight"].stringValue
                        let slabWeight = weightouts["slab_weight"].stringValue
                        let sawdustWeight = weightouts["sawdust_weight"].stringValue
                        weightOutObj.append([date,woodGrade,slabWeight,sawdustWeight])
                    }
                }
                DispatchQueue.main.async{
                    self.weightOutObj.insert(self.weightData, at: 0)
                    self.collectionview.reloadData()
                }
            }else{
                ClearAllData()
                collectionview.reloadData()
                //SetttingController.shareInstance.alertcontroller(viewcontroller: self, title: "Information", message: json[0]["data"]["message"].stringValue, titlesubmit: "OK")
            }
        }
    }
    
    func checkwoodpiecesRes(response: DataResponse<Any>) {
        hideHUD()
        dismissview()
        var parameters = [checkSawmill]()
        if let value = response.result.value {
            let json = JSON.init(value)
            let status : NSString = json[0][0]["data"]["status"].stringValue as NSString
            if status.isEqual(to: "true"){
                let ConcurrentQueue = DispatchQueue(label: "com.appformanagement.jsondownload", qos: .utility)
                ConcurrentQueue.sync {
                    let woodpiecesArray = json[1].arrayValue
                    for woodpieces in woodpiecesArray {
                        let sawId = woodpieces["sawId"].stringValue
                        let name = woodpieces["name"].stringValue
                        let param = checkSawmill(sawId:sawId,shortname:name)
                        parameters.append(param)
                    }
                }
                DispatchQueue.main.async {
                    self.createLabelAndButtom(parameter: parameters)
                }
            }else{
                dismissview()
                bottomview.isHidden = true
                ClearAllData()
                collectionview.reloadData()
            }
        }
    }
    
    func checkfirewoodRes(response: DataResponse<Any>) {
        hideHUD()
        dismissview()
        var parameters = [checkSawmill]()
        if let value = response.result.value {
            let json = JSON.init(value)
            let status : NSString = json[0][0]["data"]["status"].stringValue as NSString
            if status.isEqual(to: "true"){
                let ConcurrentQueue = DispatchQueue(label: "com.appformanagement.jsondownload", qos: .utility)
                ConcurrentQueue.sync {
                    let woodpiecesArray = json[1].arrayValue
                    for woodpieces in woodpiecesArray {
                        let sawId = woodpieces["sawId"].stringValue
                        let name = woodpieces["name"].stringValue
                        let param = checkSawmill(sawId:sawId,shortname:name)
                        parameters.append(param)
                    }
                }
                DispatchQueue.main.async {
                    self.createLabelAndButtom(parameter: parameters)
                }
            }else{
                dismissview()
                bottomview.isHidden = true
                ClearAllData()
                collectionview.reloadData()
            }
        }
    }
    
    func checkweightoutcoming(response: DataResponse<Any>) {
        hideHUD()
        dismissview()
        var parameters = [checkSawmill]()
        if let value = response.result.value {
            let json = JSON.init(value)
            let status : NSString = json[0][0]["data"]["status"].stringValue as NSString
            if status.isEqual(to: "true"){
                let ConcurrentQueue = DispatchQueue(label: "com.appformanagement.jsondownload", qos: .utility)
                ConcurrentQueue.sync {
                    let woodpiecesArray = json[1].arrayValue
                    for woodpieces in woodpiecesArray {
                        let sawId = woodpieces["sawId"].stringValue
                        let name = woodpieces["name"].stringValue
                        let param = checkSawmill(sawId:sawId,shortname:name)
                        parameters.append(param)
                    }
                }
                DispatchQueue.main.async {
                    self.createLabelAndButtom(parameter: parameters)
                }
            }else{
                dismissview()
                bottomview.isHidden = true
                ClearAllData()
                collectionview.reloadData()
            }
        }
    }
}


