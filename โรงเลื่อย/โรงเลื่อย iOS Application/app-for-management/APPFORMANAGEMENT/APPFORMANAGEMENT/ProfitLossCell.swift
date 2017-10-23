//
//  ProfitLossCell.swift
//  APPFORMANAGEMENT
//
//  Created by Chanakan Jumnongwit on 3/27/2560 BE.
//  Copyright © 2560 REVO. All rights reserved.
//

import UIKit

class ProfitLossCell: UITableViewCell {
    //@IBOutlet weak var sawImage:UIImageView!
    @IBOutlet weak var fullname:UILabel!
    @IBOutlet weak var profitTotal:UILabel!
    @IBOutlet weak var detailView:UIView!
    @IBOutlet weak var detail:UILabel!
    
    override func awakeFromNib() {
        super.awakeFromNib()
        //set image corner radius
        /*sawImage.layer.cornerRadius = 5.0*/
        //set border to UIView
        detailView.layer.borderWidth = 1
        detailView.layer.cornerRadius = 5.0
        detailView.layer.borderColor = UIColor(red:0, green:122/255.0, blue:255/255.0, alpha: 1.0).cgColor
    }

    override func setSelected(_ selected: Bool, animated: Bool) {
        super.setSelected(selected, animated: animated)

        // Configure the view for the selected state
    }

}
